document.addEventListener('DOMContentLoaded', function () {

    console.log('予約スクリプトロード完了');

    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const numberSelect = document.getElementById('number_of_people');

    const confirmDate = document.getElementById('confirm-date');
    const confirmTime = document.getElementById('confirm-time');
    const confirmNumber = document.getElementById('confirm-number');

    console.log('要素取得:', {dateInput,timeSelect,numberSelect,confirmDate, confirmTime,confirmNumber});

    if(confirmDate) {
        confirmDate.style.color = 'white';
        confirmDate.style.visibility = 'visible';
    }
    if(confirmTime) {
        confirmTime.style.color = 'white';
        confirmTime.style.visibility = 'visible';
    }
    if(confirmNumber) {
        confirmNumber.style.color = 'white';
        confirmNumber.style.visibility = 'visible';
    }

    function updateConfirmation() {

        console.log('更新実行');

        if(confirmDate) {
            confirmDate.textContent = dateInput.value;
            confirmDate.style.color = 'white';
        }
        if(confirmTime) {
            confirmTime.textContent = timeSelect.value;
            confirmTime.style.color = 'white';
        }
        if(confirmNumber) {
            confirmNumber.textContent = numberSelect.value + '人';
            confirmNumber.style.color = 'white';

        }
    }

    if(dateInput) dateInput.addEventListener('change', updateConfirmation);

    if(timeSelect) timeSelect.addEventListener('change', updateConfirmation);

    if(numberSelect) numberSelect.addEventListener('change', updateConfirmation);

    updateConfirmation();
    console.log('初期値設定完了');
});
