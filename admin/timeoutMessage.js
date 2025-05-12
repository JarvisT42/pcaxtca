 setTimeout(() => {
                            const successAlert = document.getElementById('successAlert');
                            const errorAlert = document.getElementById('errorAlert');

                            if (successAlert) {
                                successAlert.classList.remove('show');
                                successAlert.classList.add('fade');
                                successAlert.style.display = 'none';
                            }

                            if (errorAlert) {
                                errorAlert.classList.remove('show');
                                errorAlert.classList.add('fade');
                                errorAlert.style.display = 'none';
                            }
                        }, 5000);