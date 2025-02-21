document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const numberSelect = document.getElementById('number_of_people');

    const confirmDate = document.getElementById('confirm-date');
    const confirmTime = document.getElementById('confirm-time');
    const confirmNumber = document.getElementById('confirm-number');

    function updateConfirmation() {
        const date = dateInput.value;
        const time = timeSelect.value;
        const number = numberSelect.value;

        if (date) confirmDate.textContent = date;
        if (time) confirmTime.textContent = time;
        if (number) confirmNumber.textContent = number + '人';
    }

    dateInput.addEventListener('change', updateConfirmation);
    timeSelect.addEventListener('change', updateConfirmation);
    numberSelect.addEventListener('change', updateConfirmation);

    updateConfirmation();
});
