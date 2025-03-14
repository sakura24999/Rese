let stripe;
let elements;
let card;
let currentPaymentReservationId;

document.addEventListener('DOMContentLoaded', function () {
    try {
        const stripeKey = window.paymentConfig.stripeKey;
        console.log('Initializing Stripe with key:', stripeKey);

        stripe = Stripe(stripeKey);
        elements = stripe.elements();

        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        card = elements.create('card', { style: style });
        console.log('Stripe initialized successfully');
    } catch (e) {
        console.error('Stripe initialization error:', e);
        stripe = null;
    }
});

function openPaymentModal(reservationId) {
    currentPaymentReservationId = reservationId;

    const modal = document.getElementById('paymentModal');
    const reservation = document.getElementById(`reservation-${reservationId}`);

    if (!reservation) {
        console.error('reservation card not found');
        return;
    }

    const shopName = reservation.querySelector('.shop-name').textContent;
    const date = reservation.querySelector('[data-date]').getAttribute('data-date');
    const time = reservation.querySelector('[data-time]').getAttribute('data-time');
    const number = reservation.querySelector('[data-number]').getAttribute('data-number');

    // 決済金額は店舗のコースや人数によって計算する必要があります
    // ここでは仮に1人5000円として計算
    const amount = parseInt(number) * 5000;

    modal.querySelector('.shop-name').textContent = shopName;
    modal.querySelector('.reservation-date').textContent = `予約日: ${date}`;
    modal.querySelector('.reservation-time').textContent = `時間: ${time}`;
    modal.querySelector('.reservation-number').textContent = `人数: ${number}`;
    modal.querySelector('.payment-amount').textContent = `お支払い金額: ¥${amount.toLocaleString()}`;

    modal.style.display = 'block';

    if(stripe) {
        document.getElementById('card-element').style.display = 'block';
        document.getElementById('card-errors').style.display = 'block';
        card.mount('#card-element');
    }else {
        document.getElementById('card-element').style.display = 'none';
        document.getElementById('card-errors').style.display = 'none';

        const demoNoticeId = 'demo-payment-notice';
        if(!document.getElementById(demoNoticeId)) {
            const demoNotice = document.createElement('div');
            demoNotice.id = demoNoticeId;
            demoNotice.className = 'demo-notice';
            demoNotice.textContent = 'デモモード: 実際のカード情報は不要です';
            document.getElementById('card-element').parentNode.insertBefore(demoNotice, document.getElementById('card-element'));
        }else {
            document.getElementById(demoNoticeId).style.display = 'block';
        }
    }

    const form = document.getElementById('paymentForm');
    form.removeEventListener('submit', processPayment);
    form.addEventListener('submit', processPayment);
}

function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    modal.style.display = 'none';

    if (card) {
        card.unmount();
    }

    currentPaymentReservationId = null;
}

function processPayment(event) {
    event.preventDefault();

    document.getElementById('submit-payment').disabled = true;

    if(!stripe) {
        console.log('Demo mode: Processing payment without Stripe');

        fetch(`/reservations/${currentPaymentReservationId}/payment/demo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                demo_mode: true,
                amount: parseInt(document.querySelector('.reservation-number').textContent.match(/\d+/)[0]) * 5000
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('デモモード: 決済が完了しました');
                closePaymentModal();
                window.location.reload();
            } else {
                throw new Error(data.message || 'デモ決済処理に失敗しました');
            }
        })
        .catch(error => {
            console.error('Demo payment error:', error);
            alert('決済処理中にエラーが発生しました: ' + error.message);
            document.getElementById('submit-payment').disabled = false;
        });
        return;
    }

    stripe.createPaymentMethod('card', card).then(({ paymentMethod, error }) => {
        if (error) {
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            document.getElementById('submit-payment').disabled = false;
            return;
        }
        const paymentUrl = window.paymentConfig.routes.payment.replace('_ID_', currentPaymentReservationId);

        fetch(paymentUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                payment_method_id: paymentMethod.id,
                amount: parseInt(document.querySelector('.reservation-number').textContent.match(/\d+/)[0]) * 5000
            })
        })
        .then(response => {
            return response.json().then(data => ({ status: response.status, data }));
        })
        .then(({ status, data }) => {
            if (status >= 200 && status < 300 && data.success) {
                alert('決済が完了しました');
                closePaymentModal();
                window.location.reload();
            } else {
                throw new Error(data.message || '決済処理に失敗しました');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || '決済処理に失敗しました');
            document.getElementById('submit-payment').disabled = false;
        });
    });
}

function handlePayment(reservationId) {

    openPaymentModal(reservationId);
    }
window.openPaymentModal = openPaymentModal;
window.closePaymentModal = closePaymentModal;
window.processPayment = processPayment;
window.handlePayment = handlePayment;
