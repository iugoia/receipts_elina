<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Cuisine;
use App\Models\Period;
use App\Models\Receipt;
use App\Services\CountryService;
use Illuminate\Http\Request;

class AdminReceiptController extends Controller
{
    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index()
    {
        return view('admin.receipts.index');
    }

    public function bids()
    {
        return view('admin.bids.index');
    }

    public function show(Receipt $receipt)
    {
        $periods = Period::all();
        $categories = Category::all();
        $countries = $this->countryService->getAllCountries();
        $cuisines = Cuisine::all();
        return view('admin.receipts.show', compact('receipt', 'periods', 'categories', 'countries', 'cuisines'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $request->validate([
            'reason' => ['required_if:status,rejected', 'string'],
            'status' => ['required', 'string', 'in:success,rejected']
        ]);
        $receipt->update([
            'reason' => $request->reason,
            'status' => $request->status
        ]);
        if ($receipt->status == StatusEnum::REJECTED->value) {
            $chat = Chat::create([
                'user_id'    => $receipt->user_id,
                'admin_id'   => user()->id,
                'receipt_id' => $receipt->id
            ]);
            $chat->messages()->create([
                'user_id' => user()->id,
                'message' => "Ваш рецепт был отклонён. Если у вас есть вопросы, пишите сюда. Причина отклонения: {$request->reason}"
            ]);
        }
        return redirect()->route('admin.bids.index')->with('success', 'Заявка изменена');
    }

    public function delete(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->back()->with('success', 'Заявка удалена');
    }
}
