 setTimeout(() => {
        document.querySelectorAll('.auto-dismiss').forEach(alert => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            alert.style.display = 'none';
        });
    }, 5000);