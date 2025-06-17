const timelineEvents = window.env.TIMELINES.map(event => ({
    id: event.id,
    title: event.title,
    start: event.start_date,
    end: event.end_date,
    description: event.description,
    category: event.category
}));

const timelineConfig = {
    startYear: -1250,
    endYear: 2025,
    pixelsPerYear: 0.5
};

class TimelineComponent {
    constructor(containerId, events, config) {
        this.container = document.getElementById(containerId);
        this.events = events;
        this.config = config;
        this.map = null;
        this.init();
    }

    init() {
        this.setupTimeline();
        this.timelineWrapper = this.container.parentElement;
        this.renderEvents();
        this.setupNavigation();
        this.setupDragging();
        this.setupMap();
        // this.scrollToEnd();
    }

    scrollToEnd() {
        this.timelineWrapper.scrollLeft = this.container.scrollWidth - this.timelineWrapper.clientWidth;
    }

    setupMap() {
        // Инициализация карты Яндекс.Карт
        ymaps.ready(() => {
            this.map = new ymaps.Map('map', {
                center: [51.505, 0], // Начальные координаты (широта, долгота)
                zoom: 3, // Уровень масштаба
                controls: ['zoomControl', 'fullscreenControl'], // Элементы управления,
                minZoom: 2,
                maxZoom: 1
            });

            // Скрываем карту по умолчанию
            document.getElementById('map').style.display = 'none';
        });
    }

    showMap() {
        const mapContainer = document.getElementById('map');
        mapContainer.style.display = 'block';
        // Перерисовываем карту, если она была скрыта
        if (this.map) {
            this.map.container.fitToViewport();
        }
    }

    hideMap() {
        const mapContainer = document.getElementById('map');
        mapContainer.style.display = 'none';
    }

    setupTimeline() {
        const totalYears = this.config.endYear - this.config.startYear;
        const width = totalYears * this.config.pixelsPerYear + 100;
        this.container.style.width = `${width}px`;
        this.createTimelineAxis();
    }

    createTimelineAxis() {
        const axis = document.createElement('div');
        axis.className = 'timeline-axis';

        for (let year = this.config.startYear; year <= this.config.endYear; year += 500) {
            const marker = document.createElement('div');
            marker.className = 'year-marker';
            const left = (year - this.config.startYear) * this.config.pixelsPerYear;
            marker.style.left = `${left}px`;
            marker.innerHTML = `
                <div class="marker-line"></div>
                <span class="year-label">${Math.abs(year)}${year < 0 ? ' до н.э.' : ' н.э.'}</span>
            `;
            axis.appendChild(marker);
        }

        this.container.appendChild(axis);
    }

    renderEvents() {
        const sortedEvents = [...this.events].sort((a, b) => a.start - b.start);

        const cards = sortedEvents.map(event => {
            const card = this.createEventCard(event);
            return { event, card };
        });

        this.positionCardsFromBottom(cards);

        cards.forEach(({ card }) => this.container.appendChild(card));
    }

    createEventCard(event) {
        const card = document.createElement('div');
        card.className = `event-card ${event.category}`;
        card.setAttribute('data-event-id', event.id);
        card.innerHTML = `
            <h3>${event.title}</h3>
            <p>${Math.abs(event.start)}${event.start < 0 ? ' до н.э.' : ' н.э.'} -
               ${Math.abs(event.end)}${event.end < 0 ? ' до н.э.' : ' н.э.'}</p>
        `;
        card.addEventListener('click', () => this.showEventDetails(event));
        return card;
    }

    positionCardsFromBottom(cards) {
        const lanes = [];
        const BASE_LANE_HEIGHT = 100;
        const BOTTOM_MARGIN = 50;

        cards.forEach(({ event, card }) => {
            const startX = (event.start - this.config.startYear) * this.config.pixelsPerYear + 25;
            const width = Math.max((event.end - event.start) * this.config.pixelsPerYear, 100);

            let laneIndex = this.findAvailableLane(lanes, event);

            const bottomPosition = this.container.clientHeight - (laneIndex * BASE_LANE_HEIGHT) - BOTTOM_MARGIN;

            card.style.left = `${startX}px`;
            card.style.width = `${width}px`;
            card.style.bottom = `${BOTTOM_MARGIN + (laneIndex * BASE_LANE_HEIGHT)}px`;
            card.style.top = 'auto';

            if (!lanes[laneIndex]) {
                lanes[laneIndex] = [];
            }
            lanes[laneIndex].push({
                start: event.start,
                end: event.end,
                element: card
            });
        });
    }

    findAvailableLane(lanes, newEvent) {
        let laneIndex = 0;

        while (true) {
            const currentLane = lanes[laneIndex] || [];
            const hasOverlap = currentLane.some(existingEvent =>
                this.eventsOverlap(existingEvent, newEvent)
            );

            if (!hasOverlap) {
                break;
            }
            laneIndex++;
        }

        return laneIndex;
    }

    eventsOverlap(event1, event2) {
        const buffer = 100 / this.config.pixelsPerYear;
        return (event1.start - buffer <= event2.end && event2.start - buffer <= event1.end);
    }

    showEventDetails(event) {
        const details = document.getElementById('event-details');
        details.innerHTML = `
            <h3 class="event_title">${event.title}</h3>
            <p>${event.description}</p>
        `;

        this.showMap();
        fetch(`/api/recipes?period_id=${event.id}`)
            .then(response => response.json())
            .then(recipes => {
                this.updateMapWithRecipes(recipes);
            });
    }

    updateMapWithRecipes(recipes) {
        // Очищаем существующие метки
        if (this.map) {
            this.map.geoObjects.removeAll();
        }

        // Добавляем новые метки
        recipes.forEach(recipe => {
            if (recipe.latitude && recipe.longitude) {
                const placemark = new ymaps.Placemark(
                    [recipe.latitude, recipe.longitude], // Координаты метки
                    {
                        balloonContent: `
                            <h3><a href="/receipt/${recipe.id}">${recipe.title}</a></h3>
                            <p>${recipe.description}</p>
                        `
                    },
                    {
                        preset: 'islands#icon', // Стиль метки
                        iconColor: '#0095b6' // Цвет метки
                    }
                );
                this.map.geoObjects.add(placemark);
            }
        });

        // Если есть метки, центрируем карту
        if (recipes.length > 0 && recipes[0].latitude && recipes[0].longitude) {
            this.map.setCenter([recipes[0].latitude, recipes[0].longitude], 3);
        }
    }

    setupNavigation() {
        const leftBtn = document.getElementById('nav-left');
        const rightBtn = document.getElementById('nav-right');
        const wrapper = this.container.parentElement;

        const scrollAmount = 300;

        leftBtn.addEventListener('click', () => {
            wrapper.scrollLeft -= scrollAmount;
        });

        rightBtn.addEventListener('click', () => {
            wrapper.scrollLeft += scrollAmount;
        });
    }

    setupDragging() {
        let isDragging = false;
        let startX;
        let scrollLeft;
        const wrapper = this.container.parentElement;

        wrapper.addEventListener('mousedown', (e) => {
            isDragging = true;
            wrapper.style.cursor = 'grabbing';
            startX = e.pageX - wrapper.offsetLeft;
            scrollLeft = wrapper.scrollLeft;
        });

        wrapper.addEventListener('mouseleave', () => {
            isDragging = false;
            wrapper.style.cursor = 'grab';
        });

        wrapper.addEventListener('mouseup', () => {
            isDragging = false;
            wrapper.style.cursor = 'grab';
        });

        wrapper.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - wrapper.offsetLeft;
            const walk = (x - startX) * 2;
            wrapper.scrollLeft = scrollLeft - walk;
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const timeline = new TimelineComponent(
        'timeline-strip',
        timelineEvents,
        timelineConfig
    );
});
