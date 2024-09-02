
function submitAdminLogin(event) {
    event.preventDefault();
    var adminID = document.getElementById('adminID').value;
    var adminPassword = document.getElementById('adminPassword').value;

    // Create an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "login.php", true);  // Assuming you have a 'login.php' file to handle login
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Assuming the server returns 'success' on successful login
                if (xhr.responseText === 'success') {
                    window.location.href = 'admin_dashboard.php';  // Redirect to admin dashboard
                } else {
                    alert("Invalid login credentials. Please try again.");
                }
            } else {
                alert("An error occurred. Please try again.");
            }
        }
    };
    xhr.send("adminID=" + encodeURIComponent(adminID) + "&adminPassword=" + encodeURIComponent(adminPassword));
}
