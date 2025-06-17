@extends('layouts.main')

@section('title')
    Каталог
@endsection

@section('content')
    <section class="catalog">
        <div class="container">
            <h1>Каталог рецептов</h1>

            <livewire:catalog />
        </div>
    </section>
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            function initSelect2() {
                let select = $('#country-select');

                select.select2({
                    placeholder: "Выберите страну",
                    allowClear: true,
                    dropdownAutoWidth: true,
                    width: 'auto'
                });

                select.on('change', function () {
                    let component = Livewire.find($('div[wire\\:id]').attr('wire:id'));
                    component.set('country', $(this).val());
                });

                $(document).on('click', function (event) {
                    var $target = $(event.target);
                    if (
                        !$target.closest('.select2-container').length &&
                        !$target.is(select) &&
                        !$target.closest('.select2-search__field').length
                    ) {
                        select.select2('close');
                    }
                });
            }

            Livewire.hook('message.processed', () => {
                initSelect2();
            });

            initSelect2();
        });

    </script>
@endsection

@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
