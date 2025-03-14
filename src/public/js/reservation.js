function cancelReservation(reservationId) {
    if(!confirm('予約をキャンセルしてもよろしいですか？')) {
        return;
    }

    console.log(`キャンセルリクエスト開始 - 予約ID: ${reservationId}`);

    fetch(`/reservations/${reservationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('レスポンスステータス:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('サーバーレスポンス:', data);
        alert(data.message);

        const card = document.getElementById(`reservation-${reservationId}`);
        if(card) {
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();

                const reservationCards = document.querySelectorAll('.reservation-card');
                reservationCards.forEach((card, index) => {
                    const reservationNumber = card.querySelector('.reservation-number');
                    if(reservationNumber) {
                        reservationNumber.textContent = `予約${index + 1}`;
                    }
                });
            }, 300);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('キャンセルに失敗しました');
    });
}

let currentReservationId = null;

function openEditModal(reservationId) {
    currentReservationId = reservationId;
    const modal = document.getElementById('editReservationModal');
    const reservation = document.getElementById(`reservation-${reservationId}`);

    if(!reservation) {
        console.error('reservation card not found');
        return;
    }

    const date = reservation.querySelector('[data-date]').getAttribute('data-date');
    const time = reservation.querySelector('[data-time]').getAttribute('data-time');
    const number = reservation.querySelector('[data-number]').getAttribute('data-number');
    const shopName = reservation.querySelector('.shop-name').textContent;

    modal.querySelector('.shop-name').textContent = shopName;

    document.getElementById('editDate').value = date;
    document.getElementById('editTime').value = time;
    document.getElementById('editNumber').value = number;

    modal.style.display = 'block';

    generateTimeOptions();

    const timeSelect = document.getElementById('editTime');
    if(timeSelect) {
        const options = timeSelect.options;
        for (let i = 0; i < options.length; i++) {
            if(options[i].value === time) {
                timeSelect.selectedIndex = i;
                break;
            }
        }
    }
}

function closeEditModal() {
    const modal = document.getElementById('editReservationModal');
    modal.style.display = 'none';
    currentReservationId = null;
}

function generateTimeOptions() {
    const timeSelect = document.getElementById('editTime');
    timeSelect.innerHTML = '';

    for (let hour = 11; hour <= 22; hour++) {
        for (let minute of ['00', '30']) {
            if (hour === 22 && minute === '30') continue;
            const time = `${String(hour).padStart(2, '0')}:${minute}`;
            const option = new Option(time, time);
            timeSelect.add(option);
        }
    }
}

function updateReservationCard(reservationId, newData) {
    console.log('更新開始:', {
        reservationId,
        newData
    });

    const card = document.getElementById(`reservation-${reservationId}`);
    console.log('予約カード要素:', card);

    if (card) {
        const dateElement = card.querySelector('[data-date]');
        const timeElement = card.querySelector('[data-time]');
        const numberElement = card.querySelector('[data-number]');

        console.log('取得した要素:', {
            dateElement,
            timeElement,
            numberElement
        });

        if (dateElement) {
            dateElement.textContent = `Date: ${newData.date}`;
            dateElement.setAttribute('data-date', newData.date);
        }
        if (timeElement) {
            timeElement.textContent = `Time: ${newData.time}`;
            timeElement.setAttribute('data-time', newData.time);
        }
        if (numberElement) {
            numberElement.textContent = `Number: ${newData.number_of_people}人`;
            numberElement.setAttribute('data-number', newData.number_of_people);
        }
    } else {
        console.error('予約カードが見つかりません:', reservationId);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const numberSelect = document.getElementById('number_of_people');

    const confirmDate = document.getElementById('confirm-date');
    const confirmTime = document.getElementById('confirm-time');
    const confirmNumber = document.getElementById('confirm-number');

    if(dateInput && confirmDate) {
        confirmDate.textContent = dateInput.value;
    }

    if(timeSelect && confirmTime) {
        confirmTime.textContent = timeSelect.value;
    }

    if(numberSelect && confirmNumber) {
        confirmNumber.textContent = numberSelect.value + '人';
    }

    if(dateInput) {
        dateInput.addEventListener('change', function() {
            if(confirmDate)confirmDate.textContent = this.value;
        });
    }

    if(timeSelect) {
        timeSelect.addEventListener('change', function() {
            if(confirmTime)confirmTime.textContent = this.value;
        });
    }

    if(numberSelect) {
        numberSelect.addEventListener('change', function() {
            if(confirmNumber)confirmNumber.textContent = this.value + '人';
        });
    }

    const editForm = document.getElementById('editReservationForm');
    if (editForm) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = {
                date: formData.get('date'),
                time: formData.get('time'),
                number_of_people: formData.get('number_of_people')
            };

            try {
                const response = await fetch(`/reservations/${currentReservationId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    updateReservationCard(currentReservationId, data);
                    closeEditModal();
                    alert('予約を更新しました');
                    window.location.reload();
                } else {
                    throw new Error(result.message || '予約の更新に失敗しました');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || '予約の更新に失敗しました')
            }
        });
    }
});

window.cancelReservation = cancelReservation;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
