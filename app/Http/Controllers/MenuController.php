<?php

namespace App\Http\Controllers;

use App\Models\Cuisine;
use App\Models\Menu;
use App\Models\Period;
use App\Models\Receipt;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;

class MenuController extends Controller
{
    public function index()
    {
        $periods = Period::all();
        $cuisines = Cuisine::all();
        return view('menu', compact('periods', 'cuisines'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'text' => ['required', 'string']
        ]);
        $receipts = Receipt::whereApproved();

        if ($request->has('period_id') && $request->period_id !== null) {
            $receipts = $receipts->where('period_id', $request->period_id);
        }
        if ($request->has('cuisine_id') && $request->cuisine_id !== null) {
            $receipts = $receipts->where('cuisine_id', $request->cuisine_id);
        }

        $receipts = $receipts->get()->map(function ($item) {
            return [
                'id'          => $item->id,
                'name'        => $item->title,
                'description' => $item->description,
                'ingredients' => $item->ingredients,
            ];
        })->toArray();

        $receiptsJson = json_encode($receipts);

        $yourApiKey = 'sk-ZyKQFrFbNZUSr9R810HxOXrG0GLGwdfW';

        $client = new Client();

        $data = null;

        try {
            $response = $client->post('https://api.proxyapi.ru/openai/v1/chat/completions', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $yourApiKey,
                ],
                'json'    => [
                    'model'    => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system', 'content' => "Ты — AI помощник. Твоя задача — помочь пользователю создать меню рецептов на основе его запроса." .
                            "Убедись, что предоставишь релевантные рецепты. Мне нужно, чтобы ты брал рецепты из моей базы данных. " .
                            "Я тебе отправляю все рецепты с их id, названием, описанием и ингредиентами. Ингредиенты отправляются с ключами name - название ингредиента, calories - калории," .
                            "protein - белки, fat - жиры, carbs - углеводы, weight - вес. Мне нужно, чтобы ты смотрел на ингредиенты, потому что пользователь может попросить тебя о рецептах " .
                            "с определенными калориями, белками, жирами, углеводами или весом. " .
                            "Или попросить отправить веганское или вегетарианское меню, в таком случае нельзя отправлять те рецепты, которые содержат мясо. Ты обязательно должен смотреть на ингредиенты" .
                            "Ты должен отдать только id нужных рецептов в формате array без какого-либо дополнительного текста, " .
                            "комментариев или пояснений. То есть, например, ты выявил рецепты с айдишниками 1 и 2, ты должен прислать в ответ [1, 2] Твой ответ должен содержать только id рецептов. Если количество рецептов больше 10, то отправь только 10 первых. Вот рецепты: $receiptsJson"
                        ],
                        ['role' => 'user', 'content' => "Создай меню рецептов на основе следующего запроса: $request->text"],
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $data = $data['choices'][0]['message']['content'];
        } catch (RequestException $e) {
            echo "Ошибка запроса: " . $e->getMessage() . "\n";
            if ($e->hasResponse()) {
                echo "Ответ сервера: " . $e->getResponse()->getBody();
            }
        }

        if (isset($data)) {
            $ids = json_decode($data);

            if (is_array($ids)) {
                $receipts = Receipt::whereIn('id', $ids)->get();

                if ($receipts->count() == 0) {
                    return redirect('/menu#form')->with('error', 'Рецепты не найдены.');
                }

                return redirect('/menu#form')->with('receipts', $receipts)->with('text', $request->text);
            } else {
                return redirect('/menu#form')->with('error', 'Рецепты не найдены.');
            }
        } else {
            return redirect('/menu#form')->with('error', 'Ответ от AI не содержит корректных данных.');
        }
    }

    public function store(Request $request)
    {
        $selectedReceiptIds = $request->input('selected_receipts', []);

        Menu::create([
            'user_id'  => user()->id,
            'text'     => $request->text,
            'receipts' => $selectedReceiptIds
        ]);

        $request->session()->forget('receipts');
        $request->session()->forget('text');

        return redirect()->route('user.menus.index')->with('success', 'Меню успешно сохранено!');
    }

    public function show()
    {
        $menus = user()->menus;
        $menusArr = [];

        foreach ($menus as $menu) {
            $receiptIds = $menu->receipts;

            $receipts = Receipt::whereIn('id', $receiptIds)->get();

            $menusArr[] = [
                'menu'     => $menu,
                'receipts' => $receipts
            ];
        }
        return view('user.menu.index', compact('menusArr'));
    }

    public function download(Menu $menu)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addTitle('Меню: ' . $menu->title, 1);

        $section->addText(
            'Описание меню: ' . $menu->text,
            ['name' => 'Arial', 'size' => 14]
        );

        $section->addTextBreak(1);

        foreach ($menu->receipts as $receipt) {
            $receipt = Receipt::find($receipt);

            $section->addText(
                $receipt->title,
                ['name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '1F4A7D']
            );

            $section->addText(
                'Категория: ' . $receipt->category->name,
                ['name' => 'Arial', 'size' => 11, 'italic' => true, 'color' => '555555']
            );

            $section->addText('Ингредиенты:', ['name' => 'Arial', 'size' => 12, 'bold' => true]);

            $ingredients = $receipt->ingredients;

            $table = $section->addTable([
                'borderSize'  => 6,
                'borderColor' => '000000',
                'cellMargin'  => 50,
            ]);

            $table->addRow();
            $table->addCell(2000)->addText('Ингредиент', ['bold' => true, 'size' => 10]);
            $table->addCell(2000)->addText('Калории', ['bold' => true, 'size' => 10]);
            $table->addCell(2000)->addText('Белки', ['bold' => true, 'size' => 10]);
            $table->addCell(2000)->addText('Жиры', ['bold' => true, 'size' => 10]);
            $table->addCell(2000)->addText('Углеводы', ['bold' => true, 'size' => 10]);
            $table->addCell(2000)->addText('Вес', ['bold' => true, 'size' => 10]);

            foreach ($ingredients as $ingredient) {
                $table->addRow();
                $table->addCell(2000)->addText($ingredient['name'], ['size' => 9]);
                $table->addCell(2000)->addText($ingredient['calories'], ['size' => 9]);
                $table->addCell(2000)->addText($ingredient['protein'], ['size' => 9]);
                $table->addCell(2000)->addText($ingredient['fat'], ['size' => 9]);
                $table->addCell(2000)->addText($ingredient['carbs'], ['size' => 9]);
                $table->addCell(2000)->addText($ingredient['weight'] . ' г', ['size' => 9]);
            }

            $section->addTextBreak(1);
        }

        $fileName = 'menu_' . $menu->id . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $phpWord->save($filePath, 'Word2007');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
