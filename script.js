function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

function validateIDNO(input) {
    input.value = input.value.replace(/\D/g, '').substring(0, 8);
}


// ///// Logout Button
// function logout() {
//     let confirmLogout = confirm("Are you sure you want to log out?");
//     if (confirmLogout) {
//         setTimeout(function() {
//             window.location.href = 'login.php';
//         }, 100); // .1-second delay before redirection
//     }
// }

/////////Clear the Session
function logout() {
    fetch('logout.php')
        .then(response => {
            if (response.ok) {
                window.location.href = 'login.php';
            }
        });
}

// Success alert with redirect
function showSuccessAlert(message, redirectUrl) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: message,
        showConfirmButton: false,
        timer: 1500
    }).then(function() {
        window.location.href = redirectUrl;
    });
}

// Error alert
function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: message
    });
}