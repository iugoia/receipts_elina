<div class="catalog_row">
    <div class="catalog_col catalog_filters">
        <input type="text" class="auth_form_control" placeholder="Поиск.." wire:model="search">

        <label class="catalog_filter_group form_select">
            <span>Кухня</span>
            <select class="auth_form_control" wire:model="cuisine">
                @foreach($cuisines as $cuisine)
                    <option value="{{ $cuisine->id }}">{{ $cuisine->name }}</option>
                @endforeach
            </select>
        </label>

        <label class="catalog_filter_group form_select">
            <span>Период</span>
            <select class="auth_form_control" wire:model="period">
                @foreach($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->title }}</option>
                @endforeach
            </select>
        </label>

        <label class="catalog_filter_group form_select">
            <span>Категория</span>
            <select class="auth_form_control" wire:model="category">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </label>

        <button class="btn btn_danger btn_auth btn_mnt" wire:click="resetFilters">Сбросить фильтры</button>
    </div>

    <div class="catalog_col catalog_catalog">
        <ul class="main_products_list">
            @foreach($receipts as $receipt)
                <li class="main_product_item">
                    <a class="main_product_img" href="{{ route('receipt.show', $receipt) }}"  >
                        <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="product example">
                    </a>

                    <div class="main_product_info">
                        <p class="main_product_title">{{ $receipt->title }}</p>
                        <p class="main_product_subtitle">{{ $receipt->description }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-5">
            {{ $receipts->links() }}
        </div>
    </div>
</div>
