<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pick Your Seats</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .seating-area {
            flex: 3;
            padding: 20px;
            background-color: #fff;
            border-right: 2px solid #ddd;
            display: grid;
            gap: 5px;
            align-content: center;
            justify-content: center;
        }

        .seating-area {
            grid-template-columns: 40px repeat(<?= $seatsPerRow ?>, 1fr); /* Reserve space for row labels */
            grid-auto-rows: minmax(40px, 1fr); /* Dynamic row height */
        }

        .row-label {
            text-align: center;
            font-weight: bold;
            line-height: 1;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .seat {
            width: 100%;
            height: 100%;
            background-color: #28a745; /* Green for available */
            text-align: center;
            line-height: 1;
            color: white;
            font-size: 0.8rem;
            border-radius: 3px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .seat.booked {
            background-color: #6c757d; /* Dark gray for booked */
            cursor: not-allowed;
        }

        .seat.selected {
            background-color: #ffc107; /* Orange for selected */
        }

        .menu {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            box-shadow: -2px 0 6px rgba(0, 0, 0, 0.1);
            gap: 20px;
        }

        .menu h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: #343a40;
        }

        .menu p {
            margin: 0;
            font-size: 1.2rem;
            color: #555;
        }

        .menu button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            align-self: center;
        }

        .menu button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="seating-area">
            <?php
            $currentRow = 0;
            foreach ($seats as $seat):
                if ($seat['row_number'] != $currentRow):
                    $currentRow = $seat['row_number'];
                    echo "<div class='row-label'>" . chr(64 + $currentRow) . "</div>"; // Row label
                endif;
            ?>
                <div 
                    class="seat <?= $seat['is_available'] ? 'available' : 'booked' ?>" 
                    data-row="<?= $seat['row_number'] ?>" 
                    data-seat="<?= $seat['seat_number'] ?>"
                    <?= $seat['is_available'] ? '' : 'style="pointer-events: none;"' ?>
                >
                    <?= $seat['seat_number'] ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="menu">
            <h2>Booking Summary</h2>
            <?php if (isset($_SESSION['book_seat_error'])): ?>
                <div style="color: red;"><?= htmlspecialchars($_SESSION['book_seat_error']) ?></div>
                <?php unset($_SESSION['book_seat_error']); ?>
            <?php endif; ?>

            <p>Select up to 4 seats!</p>
            <p id="selected-count">Seats Selected: 0</p>
            <p id="total-price">Total Price: 0 RON</p>
            <button id="confirm-button" disabled>Confirm Booking</button>
        </div>
    </div>

    <script>
        const seats = document.querySelectorAll('.seat.available');
        const selectedCountEl = document.getElementById('selected-count');
        const totalPriceEl = document.getElementById('total-price');
        const confirmButton = document.getElementById('confirm-button');
        const maxSeats = 4;

        let selectedSeats = [];
        const ticketPrice = <?= $ticketPrice ?>;

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                const row = seat.dataset.row;
                const number = seat.dataset.seat;

                if (seat.classList.contains('selected')) {
                    seat.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(
                        s => s.row !== row || s.number !== number
                    );
                } else if (selectedSeats.length < maxSeats) {
                    seat.classList.add('selected');
                    selectedSeats.push({ row, number });
                } else {
                    alert(`You can only select up to ${maxSeats} seats.`);
                }

                updateSummary();
            });
        });

        function updateSummary() {
            const count = selectedSeats.length;
            const total = count * ticketPrice;

            selectedCountEl.textContent = `Seats Selected: ${count}`;
            totalPriceEl.textContent = `Total Price: ${total} RON`;

            confirmButton.disabled = count === 0;
        }

        confirmButton.addEventListener('click', () => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/proiect_daw_lumiere/bookings/seats';

            const screeningInput = document.createElement('input');
            screeningInput.type = 'hidden';
            screeningInput.name = 'screening_id';
            screeningInput.value = <?= $screeningId ?>;
            form.appendChild(screeningInput);

            const seatsInput = document.createElement('input');
            seatsInput.type = 'hidden';
            seatsInput.name = 'seats';
            seatsInput.value = JSON.stringify(selectedSeats); // Convert seats to JSON
            form.appendChild(seatsInput);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
</body>
</html>
