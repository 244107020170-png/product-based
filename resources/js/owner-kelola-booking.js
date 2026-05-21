document.addEventListener('DOMContentLoaded', () => {

    const rows = document.querySelectorAll('.booking-table tbody tr');

    rows.forEach(row => {

        row.addEventListener('click', () => {

            rows.forEach(r => {
                r.classList.remove('active-row');
            });

            row.classList.add('active-row');

        });

    });

});