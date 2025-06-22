<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
    <title>Station Booking by Floor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #111;
            color: white;
            text-align: center;
            overflow-x: hidden;
        }

        h1 {
            margin-top: 20px;
            font-size: 3em;
            color: #8A2BE2;
            text-shadow: 0 0 10px #8A2BE2, 0 0 20px #8A2BE2;
        }


        @keyframes flicker {

            0%,
            100% {
                opacity: 1;
                text-shadow:
                    0 0 5px #8A2BE2,
                    0 0 10px #8A2BE2,
                    0 0 20px #8A2BE2,
                    0 0 40px #8A2BE2;
            }

            50% {
                opacity: 0.6;
                text-shadow:
                    0 0 10px #8A2BE2,
                    0 0 20px #8A2BE2,
                    0 0 30px #8A2BE2,
                    0 0 50px #8A2BE2;
            }
        }


        .floor {
            margin: 40px auto;
            max-width: 800px;
            border: 2px solid #555;
            padding: 20px;
            border-radius: 10px;
        }

        .legend {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .legend-item {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: inline-block;
            margin-right: 8px;
        }

        .legend-item.booked {
            background-color: #444;
            border: 2px solid red;
        }

        .legend-item.selected {
            background-color: #8A2BE2;
        }

        .legend-item.available {
            background-color: #bbb;
        }

        .floor h2 {
            color: #8A2BE2;
        }

        .station-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .station {
            padding: 15px;
            border-radius: 8px;
            background-color: #bbb;
            cursor: pointer;
            font-weight: bold;
            transform-origin: center center;
            transform: scale(1);
            transition: background-color 0.3s ease;
        }

        .station:hover:not(.booked):not(.selected) {
            background-color: #ccc;
        }

        .station.booked {
            background-color: #444;
            color: #bbb;
            border: 2px solid red;
            pointer-events: none;
            opacity: 0.6;
            cursor: not-allowed;
        }

        .station.selected {
            background-color: #8A2BE2;
            color: white;
        }

        /*.station.ps5 {
            background-image: url('images/ps5_topview.png');
            background-size: cover;
        }

        .station.ps4 {
            background-image: url('images/ps4_topview.png');
            background-size: cover;
        }

        .station.xbox {
            background-image: url('images/xbox_topview.png');
            background-size: cover;
        }

        .station.pool {
            background-image: url('images/pool_topview.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .station.snooker {
            background-image: url('images/snooker_topview.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }*/

        .calendar {
            margin: 20px auto;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        select,
        input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            outline: none;
        }

        .btn {
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #8A2BE2;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #a14ef0;
        }
    </style>
</head>

<body>
    <h1>Select Station</h1>

    <div class="legend">
        <span class="legend-item booked"></span> Booked
        <span class="legend-item selected"></span> Selected
        <span class="legend-item available"></span> Available
    </div>

    <div class="calendar">
        <label>Select Booking Date</label>
        <input type="date" id="booking_date" name="booking_date" required>

        <label>Select Booking Start Time</label>
        <select id="start_time" name="start_time" required></select>

        <label>Select Booking End Time</label>
        <select id="end_time" name="end_time" required></select>
    </div>

    <!-- Floor 1 -->
    <div class="floor">
        <h2>🎮 Floor 1</h2>
        <div class="station-grid" id="floor1"></div>
    </div>

    <!-- Floor 2 -->
    <div class="floor">
        <h2>🔥 Floor 2</h2>
        <div class="station-grid" id="floor2"></div>
    </div>

    <!-- Floor 3: PS5 + PS4 -->
    <div class="floor">
        <h2>🕹️ Floor 3</h2>
        <div class="station-grid" id="floor3"></div>
    </div>

    <!-- Floor 4: Xbox + Pool + Snooker -->
    <div class="floor">
        <h2>🎱 Floor 4</h2>
        <div class="station-grid" id="floor4"></div>
    </div>

    <form id="bookingForm" action="process_booking.php" method="POST">
        <input type="hidden" name="station_id" id="stationInput">
        <input type="hidden" name="start_time_hidden" id="startTimeInput">
        <input type="hidden" name="end_time_hidden" id="endTimeInput">
        <button type="submit" class="btn">Confirm Booking</button>
    </form>

    <script>
        anime({
            targets: 'h1',
            opacity: [{
                    value: 0.5,
                    duration: 100
                },
                {
                    value: 1,
                    duration: 100
                },
                {
                    value: 0.7,
                    duration: 80
                },
                {
                    value: 1,
                    duration: 100
                },
            ],
            easing: 'linear',
            loop: true
        });


        function animateStationsFadeIn(containerId) {
            const stations = document.querySelectorAll(`#${containerId} .station`);
            const floor = document.getElementById(containerId).closest('.floor');

            anime({
                targets: stations,
                opacity: [0, 1],
                translateY: [20, 0],
                delay: anime.stagger(100),
                easing: 'easeOutQuad',
                duration: 800
            });

            anime({
                targets: floor,
                scale: [0.9, 1],
                opacity: [0, 1],
                easing: 'easeOutQuad',
                duration: 600
            });
        }

        function animateFloorWithStations(containerId) {
            const container = document.getElementById(containerId);
            const stations = container.querySelectorAll('.station');

            // Animate floor container: zoom in and fade in
            anime({
                targets: container,
                scale: [0.9, 1],
                opacity: [0, 1],
                easing: 'easeOutQuad',
                duration: 600
            });

            // Animate stations with staggered fade + translateY
            anime({
                targets: stations,
                opacity: [0, 1],
                translateY: [20, 0],
                delay: anime.stagger(100),
                easing: 'easeOutQuad',
                duration: 800
            });
        }

        function pad(n) {
            return n < 10 ? '0' + n : n;
        }

        function formatLocalDateTime(date) {
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        }

        function generateTimeOptions(selectId) {
            const select = document.getElementById(selectId);
            select.innerHTML = '';
            const startHour = 8;
            const endHour = 23;
            for (let hour = startHour; hour <= endHour; hour++) {
                for (let min = 0; min < 60; min += 15) {
                    const label = `${pad(hour)}:${pad(min)}`;
                    const option = document.createElement('option');
                    option.value = label;
                    option.textContent = label;
                    select.appendChild(option);
                }
            }
        }

        async function fetchBookedStations(date, startTime, endTime) {
            try {
                const res = await fetch(`get_booked_stations.php?date=${encodeURIComponent(date)}&start_time=${encodeURIComponent(startTime)}&end_time=${encodeURIComponent(endTime)}`);
                const data = await res.json();
                return data.booked || [];
            } catch (e) {
                console.error('Error fetching booked stations:', e);
                return [];
            }
        }

        function renderStations(start, end, containerId, bookedStations) {
            const grid = document.getElementById(containerId);
            grid.innerHTML = '';

            for (let i = start; i <= end; i++) {
                const div = document.createElement('div');
                div.className = 'station';
                div.dataset.id = i;

                // Detect type label
                let label = '';

                if (containerId === 'floor1') {
                    label = 'PC';
                } else if (containerId === 'floor2') {
                    label = 'Premium PC';
                } else if (containerId === 'floor3') {
                    if (i >= 21 && i <= 24) label = 'PS5';
                    else if (i >= 25 && i <= 26) label = 'PS4';
                } else if (containerId === 'floor4') {
                    if (i >= 27 && i <= 28) label = 'Xbox';
                    else if (i >= 29 && i <= 32) label = 'Pool';
                    else if (i >= 33 && i <= 34) label = 'Snooker';
                }

                // Add station number and label
                div.innerHTML = `<div>Station ${i}</div><small style="font-size: 12px; opacity: 0.7;">${label}</small>`;

                if (bookedStations.includes(i)) {
                    div.classList.add('booked');
                    div.style.pointerEvents = 'none';
                } else {
                    div.addEventListener('click', () => {
                        if (div.classList.contains('selected')) {
                            // If already selected, deselect it
                            anime({
                                targets: div,
                                scale: 1,
                                duration: 300,
                                easing: 'easeOutQuad',
                                complete: () => div.classList.remove('selected')
                            });
                        } else {
                            // Select the station
                            anime({
                                targets: div,
                                scale: 1.2,
                                duration: 300,
                                easing: 'easeOutQuad',
                                begin: () => div.classList.add('selected')
                            });
                        }

                        // Update hidden input with all selected station IDs
                        const selectedIds = Array.from(document.querySelectorAll('.station.selected'))
                            .map(el => el.dataset.id);
                        document.getElementById('stationInput').value = selectedIds.join(',');
                    });

                }

                grid.appendChild(div);
            }
        }


        async function updateLayout() {
            const dateInput = document.getElementById('booking_date').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;

            if (dateInput && startTime && endTime) {
                const bookedStations = await fetchBookedStations(dateInput, startTime, endTime);
                renderStations(1, 10, 'floor1', bookedStations);
                animateStationsFadeIn('floor1');
                renderStations(11, 20, 'floor2', bookedStations);
                animateStationsFadeIn('floor2');
                renderStations(21, 26, 'floor3', bookedStations);
                setTimeout(() => {
                    animateFloorWithStations('floor3');
                }, 100);
                renderStations(27, 34, 'floor4', bookedStations);
                setTimeout(() => {
                    animateFloorWithStations('floor4');
                }, 500);
            }
        }

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const date = document.getElementById('booking_date').value;
            const start = document.getElementById('start_time').value;
            const end = document.getElementById('end_time').value;
            const station = document.getElementById('stationInput').value;

            if (!date || !start || !end || !station) {
                alert('Please select a date, start time, end time, and station before booking.');
                e.preventDefault();
                return;
            }

            document.getElementById('startTimeInput').value = `${date}T${start}`;
            document.getElementById('endTimeInput').value = `${date}T${end}`;
        });

        window.addEventListener('DOMContentLoaded', () => {
            generateTimeOptions('start_time');
            generateTimeOptions('end_time');

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('booking_date').value = today;

            updateLayout(); // load layout with today's date
        });

        ['booking_date', 'start_time', 'end_time'].forEach(id => {
            document.getElementById(id).addEventListener('change', updateLayout);
        });


        div.addEventListener('click', () => {
            document.querySelectorAll('.station.selected').forEach(s => {
                anime({
                    targets: s,
                    scale: 1,
                    duration: 300,
                    easing: 'easeOutQuad',
                    complete: () => s.classList.remove('selected')
                });
            });

            anime({
                targets: div,
                scale: 1.2,
                duration: 300,
                easing: 'easeOutQuad',
                begin: () => div.classList.add('selected')
            });

            document.getElementById('stationInput').value = i;
        });
    </script>

</body>

</html>