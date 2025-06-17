@extends('layouts.main')

@section('title')
    Главная
@endsection

@section('content')

    <style>
        header {
            color: white;
        }

        .burger span {
            background: #fafafa;
        }

        .burger.active span {
            background: black;
        }
    </style>

    <section class="promo promo_index">
        <div class="container z-2">
            <div class="promo_content">
                <h1>гастрономическое путешествие во времени</h1>
                <div class="mini_hr"></div>
                <p class="promo_desc">Добро пожаловать в мир кулинарной истории!</p>
            </div>
        </div>
    </section>

    <article class="promo_additional">
        <div class="container">
            <div class="swiper-container promo_additional_list">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="promo_additional_item">
                            <div class="promo_add_img">
                                <img src="{{ asset('img/add_1.svg') }}" alt="add 1">
                            </div>
                            <div class="promo_add_info">
                                <p class="promo_add_title">Средневековая кухня</p>
                                <p class="promo_add_desc">Откройте для себя вкусы средневековой Европы: изысканные пиры, блюда из дичи и древние рецепты.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="promo_additional_item">
                            <div class="promo_add_img">
                                <img src="{{ asset('img/add_2.svg') }}" alt="add 2">
                            </div>
                            <div class="promo_add_info">
                                <p class="promo_add_title">Античные рецепты</p>
                                <p class="promo_add_desc">Погрузитесь в мир кулинарии Древнего Рима и Греции – попробуйте блюда, которыми наслаждались императоры.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="promo_additional_item">
                            <div class="promo_add_img">
                                <img src="{{ asset('img/add_3.svg') }}" alt="add 3">
                            </div>
                            <div class="promo_add_info">
                                <p class="promo_add_title">Традиции Востока</p>
                                <p class="promo_add_desc">Насладитесь богатым наследием восточной кухни – от пряных блюд до сладостей, покоривших мир.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="promo_additional_item">
                            <div class="promo_add_img">
                                <img src="{{ asset('img/add_4.png') }}" alt="add 4">
                            </div>
                            <div class="promo_add_info">
                                <p class="promo_add_title">Кухня эпохи Возрождения</p>
                                <p class="promo_add_desc">Исследуйте кулинарные шедевры Ренессанса: изысканные сочетания специй, сладостей и блюд для королевских столов.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="promo_additional_item">
                            <div class="promo_add_img">
                                <img src="{{ asset('img/add_5.png') }}" alt="add 5">
                            </div>
                            <div class="promo_add_info">
                                <p class="promo_add_title">Вкус Древнего Египта</p>
                                <p class="promo_add_desc">Попробуйте рецепты фараонов: от хлеба на меду до блюд с экзотическими приправами Нила.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="promo_additional_item">
                            <div class="promo_add_img">
                                <img src="{{ asset('img/add_6.png') }}" alt="add 6">
                            </div>
                            <div class="promo_add_info">
                                <p class="promo_add_title">Кулинария викингов</p>
                                <p class="promo_add_desc">Ощутите суровый дух Севера: простые, но сытные блюда из рыбы, мяса и даров леса.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <div class="swiper-pagination"></div>
            </div>
        </div>
    </article>

    <section class="about" id="about">
        <div class="container">
            <div class="about_row">
                <div class="about_col about_col_img">
                    <div class="about_img">
                        <img src="{{ asset('img/about.png') }}" alt="about">
                    </div>
                    <div class="about_img_content">
                        <p class="about_img_title">{{ $receiptsCount }}</p>
                        <p class="about_img_desc">Рецептов</p>
                        <p class="about_img_title">{{ $cuisinesCount }}</p>
                        <p class="about_img_desc">Видов кухонь</p>
                    </div>
                </div>

                <div class="about_col about_col_content">
                    <h2>Немного о нас</h2>
                    <div class="mini_hr"></div>
                    <p class="about_col_subtitle">Самые незабываемо вкусные новинки</p>
                    <p class="about_col_desc">Гастрономические путешествия во времени — это уникальная возможность
                        погрузиться в прошлое через вкус и ароматы. На нашем сайте вы найдете рецепты исторических блюд
                        из самых разных эпох и регионов мира, от древнего Египта до европейского Ренессанса, от вкусов
                        Японии эпохи Эдо до кулинарных шедевров арабского Востока.
                        Мы стремимся к тому, чтобы каждый рецепт на нашем сайте рассказывал историю: от древнейших
                        времен до наших дней. Наши кулинарные исследования основаны на исторических источниках,
                        фольклоре и археологических находках, позволяя вам погрузиться в атмосферу старины, создавая
                        настоящие гастрономические шедевры на своей кухне.</p>
                    <p class="about_col_desc">Мы — команда энтузиастов кулинарной истории, вдохновленных идеей оживить
                        вкусы прошлого и познакомить вас с кулинарными традициями разных культур и эпох. Наш проект,
                        Гастрономические путешествия во времени, создан для того, чтобы раскрыть секреты старинных
                        рецептов, показать, как развивалась кухня, и напомнить о том, что каждое блюдо — это не только
                        сочетание вкусов, но и отражение целой эпохи, её культуры и традиций.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="index_timeline">
        <div class="container">
            <h2>Исторические периоды</h2>
            <div class="mini_hr"></div>
            <p class="index_timeline_subtitle">Путешествуйте во времени через вкусы</p>
            <div class="timeline_teaser">
                <p>Откройте для себя кулинарные традиции разных эпох — от древних рецептов фараонов до блюд эпохи Возрождения. Исследуйте интерактивные карты с рецептами со всего мира!</p>
                <div class="teaser_items">
                    @foreach($periods as $index => $period)
                        <div class="teaser_item"
                             data-title="{{ $period->title }}"
                             data-description="{{ $period->description }}"
                             data-years="{{ \Carbon\Carbon::parse($period->start_date)->format('Y') }} – {{ \Carbon\Carbon::parse($period->end_date)->format('Y') }}">
                            <img src="{{ asset('img/periods/' . ($index + 1) . '.jpg') }}" alt="{{ $period->title }}">
                            <span>{{ $period->title }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('map') }}" class="btn btn_primary">Перейти</a>
        </div>
    </section>

    @if($cuisines->count() > 0)
        <section class="kitchens">
            <div class="container">
                <h2>Наши кухни</h2>
                <div class="mini_hr"></div>
                <p class="receipts_subtitle">Выберите кухню, которая вам нравится</p>
                <div class="kitchen_list">
                    @foreach($cuisines as $item)
                        <div class="kitchen_item">
                            <a href="{{ route('cuisine.show', $item) }}" class="slider-img"  >
                                <img src="{{ asset('img/cuisines/' . $item->photo) }}" alt="{{ $item->name }}">
                            </a>
                            <a href="{{ route('cuisine.show', $item) }}" class="kitchen_description">
                                <p>{{ $item->description }}</p>
                            </a>
                            <a href="{{ route('cuisine.show', $item) }}" class="kitchen_link"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

{{--    @if($randomReceipts->count() > 0)--}}
{{--        <section class="main_products">--}}
{{--            <div class="container">--}}
{{--                <h2>Вкусно, быстро и полезно!</h2>--}}
{{--                <div class="mini_hr"></div>--}}
{{--                <p class="subtitle">Наши рецепты:</p>--}}

{{--                <ul class="main_products_list">--}}
{{--                    @foreach($randomReceipts as $receipt)--}}
{{--                        <li class="main_product_item">--}}
{{--                            <div class="main_product_img">--}}
{{--                                <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="product example">--}}
{{--                            </div>--}}

{{--                            <div class="main_product_info">--}}
{{--                                <p class="main_product_title">{{ $receipt->title }}</p>--}}
{{--                                <p class="main_product_subtitle">{{ $receipt->description }}</p>--}}
{{--                                <a href="{{ route('receipt.show', $receipt) }}" class="btn btn_primary"  >Подробнее</a>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </section>--}}
{{--    @endif--}}

    @if($popularReceipts->count() > 0)
        <section class="receipts">
            <div class="container">
                <h2>Популярные рецепты</h2>
                <div class="mini_hr"></div>
                <p class="receipts_subtitle">Выбирая нас, вы выбираете качество</p>

                <ul class="receipts_list">
                    @foreach($popularReceipts as $receipt)
                        <li class="receipt_item">
                            <div class="receipt_img">
                                <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="receipt">
                            </div>
                            <div class="receipt_content">
                                <p class="receipt_title">{{ $receipt->title }}</p>
                                <p class="receipt_ingredients">Ингридиенты</p>
                                <ul class="receipt_ingredients_list">
                                    @foreach($receipt->ingredients as $ingredient)
                                        <li class="receipt_ingredients_item">{{ $ingredient['name'] }}
                                            - {{ $ingredient['weight'] }} г
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('receipt.show', $receipt) }}" class="btn btn_primary"  >Подробнее</a>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('catalog') }}" class="btn btn_primary catalog_link">Перейти ко всем рецептам</a>
            </div>
        </section>
    @endif

{{--    <section class="form" id="form">--}}
{{--        <div class="container">--}}
{{--            <div class="form_content">--}}
{{--                <h2>У вас остались вопросы? не беда, пишите нам!</h2>--}}
{{--                <div class="mini_hr"></div>--}}
{{--                <p class="form_subtitle">Мы будем рады вам помочь!</p>--}}
{{--                @if(session()->has('success'))--}}
{{--                    <p class="green mt-3">{{ session()->get('success') }}</p>--}}
{{--                @endif--}}
{{--                @if($errors->any())--}}
{{--                    <div class="mt-3">--}}
{{--                        @foreach($errors->all() as $error)--}}
{{--                            <p class="red mb-1">{{ $error }}</p>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--                <form action="{{ route('question.store') }}" class="form_form" method="post">--}}
{{--                    @csrf--}}
{{--                    <fieldset class="form_group">--}}
{{--                        <input type="text" class="form_control" placeholder="Ваше имя*" name="name" required>--}}
{{--                        <input type="text" class="form_control" placeholder="Телефон*" id="phone" name="phone" required>--}}
{{--                    </fieldset>--}}
{{--                    <label class="checkbox_label">--}}
{{--                        <input type="checkbox" class="form_checkbox" name="checkbox" required>--}}
{{--                        <span class="checkbox_span">Ознакомлен с пользовательским соглашением</span>--}}
{{--                    </label>--}}
{{--                    <button class="btn btn_primary" type="submit">Оставить заявку</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <section class="faq_section">
        <div class="container">
            <h2>Часто задаваемые вопросы</h2>
            <div class="faq_list">
                <div class="faq_item">
                    <div class="faq_question">Что такое исторические рецепты?</div>
                    <div class="faq_answer">
                        Это блюда, которые были популярны в разные эпохи — от Древнего мира до наших дней. Мы собрали рецепты, основанные на исторических источниках, чтобы вы могли попробовать вкус прошлого.
                    </div>
                </div>
                <div class="faq_item">
                    <div class="faq_question">Как пользоваться картой рецептов?</div>
                    <div class="faq_answer">
                        На странице "Карта" вы найдёте слайдер с периодами. Выберите эпоху, и карта покажет метки с рецептами. Кликните на метку, чтобы увидеть название, описание и перейти к полному рецепту.
                    </div>
                </div>
                <div class="faq_item">
                    <div class="faq_question">Могу ли я добавить свой рецепт?</div>
                    <div class="faq_answer">
                        Да, после авторизации на сайте вы можете добавить свой рецепт! Он будет отправлен на модерацию, и после одобрения появится на сайте.
                    </div>
                </div>
                <div class="faq_item">
                    <div class="faq_question">Зачем нужны координаты на карте?</div>
                    <div class="faq_answer">
                        Координаты показывают, где эти блюда были популярны или откуда они происходят. Это помогает лучше понять кулинарную историю мира.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        header .register_buttons {
            color: var(--white);
        }
    </style>

    <div id="periodModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 id="modalTitle"></h3>
            <p class="modal-years" id="modalYears"></p>
            <p id="modalDescription"></p>
        </div>
    </div>

@endsection

@section('custom_js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{ asset('js/timeline.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('periodModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalYears = document.getElementById('modalYears');
            const modalDescription = document.getElementById('modalDescription');
            const closeBtn = document.querySelector('.modal-close');

            document.querySelectorAll('.teaser_item').forEach(item => {
                item.addEventListener('click', () => {
                    modalTitle.textContent = item.dataset.title;
                    modalYears.textContent = item.dataset.years;
                    modalDescription.textContent = item.dataset.description;
                    modal.classList.add('open');
                });
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.remove('open');
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.remove('open');
            });
        });

        $(document).ready(function () {
            $('.kitchen-slider').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: true,
                dots: true,
                adaptiveHeight: true,
            });
            $('#phone').mask('+7 (000) 000-00-00');
        });
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 70,
            slidesPerGroup: 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                type: 'bullets',
            },
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            breakpoints: {
                // when window width is < 1300px
                0: {
                    slidesPerView: 1,
                    spaceBetween: 40,
                },
                1000: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1300: {
                    slidesPerView: 3,
                    spaceBetween: 70,
                },
            },
        });

        $(document).ready(function() {
            $('.faq_question').on('click', function() {
                const $item = $(this).parent('.faq_item');
                const $answer = $item.find('.faq_answer');

                $('.faq_item').not($item).removeClass('active');
                $('.faq_answer').not($answer).css('max-height', 0).css('padding', '0 20px');

                $item.toggleClass('active');
                if ($item.hasClass('active')) {
                    $answer.css('max-height', $answer[0].scrollHeight + 'px');
                } else {
                    $answer.css('max-height', 0);
                }
            });
        });
    </script>
@endsection

@section('custom_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
@endsection
