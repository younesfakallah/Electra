
    const passInput = document.getElementById('tinput');
    const togglePassword = document.querySelector("#togglePassword");

    // Si l'utilisateur clique sur l'icone il verra son mot de passe en clair
        togglePassword.addEventListener("click", function () {
            const type = passInput.getAttribute("type") === "password" ? "text" : "password";
            passInput.setAttribute("type", type);

            this.classList.toggle("eye");
        });

        let errorAccpass;

        const checkRegex = (input) => {
            if(input.getAttribute('type') === "password") {
                    if(/[A-Z]{1,5}[a-z]{1,5}(?=.*[0-9]{1,5})(?=.*[^\w\d]{1,5})/.test(input.value)) {
                        errorAccpass = 0;
                        passInput.style.border = "solid 1px #5AFF15";
                    } else {
                        errorAccpass = 1;
                        passInput.style.border = "solid 1px #C42021";
                    }
            }
        }
        
        passInput.onkeyup = () => {
            checkRegex(passInput);
        };

        document.getElementById('submitt').addEventListener('click', (e) => {
            if(errorAccpass === 0) {

            } else {
                e.preventDefault();
                let error = document.getElementById('notif');
                if(passInput.style.border === "") {
                    error.innerHTML = "<div class='errormsg'>Veuillez entrer un mot de passe</div>";
                } else {
                    error.innerHTML = "<div class='errormsg'>Le mot de passe doit comporter 8 caractères minimum (majuscule, minuscule, caractères spécial, nombre)</div>";
                }
            }
        });