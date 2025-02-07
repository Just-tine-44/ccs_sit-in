function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

function validateIDNO(input) {
    input.value = input.value.replace(/\D/g, '').substring(0, 8);
}


///// Logout Button
function logout() {
    alert('Logging Out');
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 100); // .1-second delay before redirection
}