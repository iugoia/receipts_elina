$(document).ready(function () {
    let products = [];
    let typingTimer;
    let doneTypingInterval = 500;

    $("#product-search").on("input", function () {
        clearTimeout(typingTimer);

        let query = $(this).val().trim();
        if (query.length < 2) {
            $("#results-container").hide();
            return;
        }

        typingTimer = setTimeout(function () {

            $.ajax({
                url: "/api/receipts/calories",
                method: "POST",
                data: {term: query},
                success: function (response) {
                    let productList = $("#product-list").empty();
                    if (response.status === 200 && response.body && response.body.results) {

                        const products = response.body.results;
                        if (products.length === 0) {
                            let item = "<li><div><strong>Ничего не найдено</strong></div></li>";
                            productList.append(item);
                        }

                        $("#results-container").show();

                        products.forEach(product => {
                            let item = $("<li class='list-group-item list-group-item-action'></li>")
                                .html(
                                    "<div>" +
                                    "<div class='table_product_name'>" +
                                    product.text +
                                    "</div>" +
                                    "<div class='table_product_calories'>" +
                                    "<span style='color: #AAA'>Калорийность </span>" +
                                    product.cal + ", " +
                                    "<span style='color: #AAA'>Б: </span>" +
                                    product.bel + ", " +
                                    "<span style='color: #AAA'>Ж: </span>" +
                                    product.jir + ", " +
                                    "<span style='color: #AAA'>У: </span>" +
                                    product.ugl + ", " +
                                    "</div>" +
                                    "</div>"
                                )
                                .data("product", {
                                    name: product.text,
                                    calories: product.cal,
                                    protein: product.bel,
                                    fat: product.jir,
                                    carbs: product.ugl
                                });
                            productList.append(item);
                        });
                    } else {
                        console.error('Не удалось найти результаты:', response.body);
                        $("#results-container").hide();
                    }
                },
                error: function () {
                    console.error("Ошибка запроса к API.");
                    $("#results-container").hide();
                }
            });
        }, doneTypingInterval);
    });

    // Выбор продукта из списка
    $("#product-list").on("click", "li", function (e) {
        e.preventDefault();
        let product = $(this).data("product");
        addProductToTable(product);
        $("#product-list").empty();
        $("#product-search").val("");
        $("#results-container").hide();
    });

    function addProductToTable(product) {
        let row = $("<tr></tr>").data("product", product);

        row.append(`<td>${product.name}</td>`);
        row.append(`<td><input type="number" class="auth_form_control weight" value="${product.weight}"></td>`);
        row.append(`<td class="calories">${product.calories}</td>`);
        row.append(`<td class="protein">${product.protein}</td>`);
        row.append(`<td class="fat">${product.fat}</td>`);
        row.append(`<td class="carbs">${product.carbs}</td>`);
        row.append(`<td><button class="btn btn-danger btn-sm remove">X</button></td>`);

        console.log(product.weight);

        $("#product-table").append(row);
        updateTotals();
    }

    $("#product-table").on("click", ".remove", function () {
        $(this).closest("tr").remove();
        updateTotals();
    });

    $("#product-table").on("input", ".weight", function () {
        let row = $(this).closest("tr");
        let weight = parseFloat($(this).val()) || 0;
        let product = row.data("product");

        row.find(".calories").text((product.calories * weight / 100).toFixed(1));
        row.find(".protein").text((product.protein * weight / 100).toFixed(1));
        row.find(".fat").text((product.fat * weight / 100).toFixed(1));
        row.find(".carbs").text((product.carbs * weight / 100).toFixed(1));

        updateTotals();
    });

    function updateTotals() {
        let totalWeight = 0, totalCal = 0, totalProtein = 0, totalFat = 0, totalCarbs = 0;

        $("#product-table tr").each(function () {
            let weight = parseFloat($(this).find(".weight").val()) || 0;
            totalWeight += weight;
            totalCal += parseFloat($(this).find(".calories").text()) || 0;
            totalProtein += parseFloat($(this).find(".protein").text()) || 0;
            totalFat += parseFloat($(this).find(".fat").text()) || 0;
            totalCarbs += parseFloat($(this).find(".carbs").text()) || 0;
        });

        $("#total-cal").text(totalCal.toFixed(1));
        $("#total-protein").text(totalProtein.toFixed(1));
        $("#total-fat").text(totalFat.toFixed(1));
        $("#total-carb").text(totalCarbs.toFixed(1));

        let factor = totalWeight > 0 ? 100 / totalWeight : 0;
        $("#total-cal-100").text((totalCal * factor).toFixed(1));
        $("#total-protein-100").text((totalProtein * factor).toFixed(1));
        $("#total-fat-100").text((totalFat * factor).toFixed(1));
        $("#total-carb-100").text((totalCarbs * factor).toFixed(1));
    }

    let ingredients = JSON.parse($('#ingredients-data').val());

    ingredients.forEach(function (ingredient) {
        addProductToTable(ingredient);
    });
});

$("#receipt-form").on("submit", function (e) {
    e.preventDefault();

    let ingredients = [];

    $("#product-table tr").each(function () {
        let product = $(this).data("product");
        let weight = $(this).find(".weight").val();

        ingredients.push({
            name: product.name,
            calories: product.calories * weight / 100,
            protein: product.protein * weight / 100,
            fat: product.fat * weight / 100,
            carbs: product.carbs * weight / 100,
            weight: weight
        });
    });

    $("#ingredients-data").val(JSON.stringify(ingredients));

    this.submit();
});
