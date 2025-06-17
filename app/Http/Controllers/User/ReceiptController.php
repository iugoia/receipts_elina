<?php

namespace App\Http\Controllers\User;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptStoreRequest;
use App\Http\Requests\ReceiptUpdateRequest;
use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Period;
use App\Models\Receipt;
use App\Services\CountryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReceiptController extends Controller
{
    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index()
    {
        return view('user.receipts.index');
    }

    public function create()
    {
        $periods = Period::all();
        $countries = $this->countryService->getAllCountries();
        $categories = Category::all();
        $cuisines = Cuisine::all();
        return view('user.receipts.create', compact('periods', 'countries', 'categories', 'cuisines'));
    }

    public function store(ReceiptStoreRequest $request)
    {
        $data = $request->only('title', 'period_id', 'category_id', 'country_code', 'cuisine_id', 'description', 'latitude', 'longitude');
        $data['instructions'] = $request->input('instructions');

        $ingredients = json_decode($request->input('ingredients'), true);

        $receipt = user()->receipts()->create([
                'ingredients' => $ingredients,
            ] + $data);
        if (user()->is_admin) {
            $receipt->status = StatusEnum::SUCCESS->value;
            $receipt->save();
        }
        if ($request->hasFile('image')) {
            $receipt->addMediaFromRequest('image')->toMediaCollection('images');
        }
        return redirect()->back()->with('success', 'Рецепт успешно создан!');
    }

    public function edit(Receipt $receipt)
    {
        $periods = Period::all();
        $categories = Category::all();
        $countries = $this->countryService->getAllCountries();
        $cuisines = Cuisine::all();
        return view('user.receipts.edit', compact('receipt', 'periods', 'categories', 'countries', 'cuisines'));
    }

    public function update(ReceiptUpdateRequest $request, Receipt $receipt)
    {
        $data = $request->except('image');
        $ingredients = json_decode($request->input('ingredients'), true);
        $receipt->update([
                'ingredients' => $ingredients,
            ] + $data);
        if ($request->hasFile('image')) {
            $receipt->addMediaFromRequest('image')->toMediaCollection('images');
        }

        $receipt->update(['status' => StatusEnum::NEW->value]);

        return redirect()->back()->with('success', 'Рецепт успешно обновлен! Ожидайте модерации.');
    }

    public function delete(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('user.receipts.index')->with('success', 'Рецепт удален');
    }

    public function calories(Request $request)
    {
        $term = $request->input('term', '');
        $url = 'https://calculat.ru/wp-content/themes/EmptyCanvas/db123.php';

        $response = Http::withHeaders([
            'Content-Type'     => 'application/x-www-form-urlencoded; charset=UTF-8',
            'Origin'           => 'https://calculat.ru',
            'Referer'          => 'https://calculat.ru/calculator-kalorijnosti-produktov',
            'User-Agent'       => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->asForm()->post($url, [
            'term'  => $term,
            '_type' => 'query',
            'q'     => $term,
        ]);

        return response()->json([
            'status' => $response->status(),
            'body'   => json_decode($response->body(), true),
        ]);
    }
}
