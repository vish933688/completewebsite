document.getElementById('myform').addEventListener('submit', function(event) {
    let valid = true;

    // Clear previous errors
    document.getElementById('nameError').textContent = '';
    document.getElementById('addresserror').textContent = '';
    document.getElementById('mobilenoerror').textContent = '';
    document.getElementById('subjecterror').textContent = '';
    document.getElementById('salaryerror').textContent = '';
    document.getElementById('joined_dateerror').textContent = '';
    document.getElementById('bank_account_noerror').textContent = '';

    // Validate Name
    const name = document.getElementById('name').value.trim();
    if (name === '') {
        document.getElementById('nameError').textContent = 'Name is required.';
        valid = false;
    }

    // Validate Address (Optional)
    const address = document.getElementById('address').value.trim();
    if (address.length > 0 && address.length < 5) {
        document.getElementById('addresserror').textContent = 'Address must be at least 5 characters long.';
        valid = false;
    }

    // Validate Mobile Number
    const mobile = document.getElementById('mobileno').value.trim();
    const mobilePattern = /^\+?[1-9]\d{1,14}$/; // General international format
    if (mobile.length > 0 && !mobilePattern.test(mobile)) {
        document.getElementById('mobilenoerror').textContent = 'Invalid mobile number format.';
        valid = false;
    }

    // Validate Subject
    const subject = document.getElementById('subject').value.trim();
    if (subject === '') {
        document.getElementById('subjecterror').textContent = 'Subject is required.';
        valid = false;
    }

    // Validate Salary
    const salary = parseFloat(document.getElementById('salary').value);
    if (isNaN(salary) || salary <= 0) {
        document.getElementById('salaryerror').textContent = 'Salary must be a positive number.';
        valid = false;
    }

    // Validate Joined Date
    const joinedDate = new Date(document.getElementById('joined_date').value);
    const today = new Date();
    if (joinedDate > today) {
        document.getElementById('joined_dateerror').textContent = 'Joined date cannot be in the future.';
        valid = false;
    }

    // Validate Bank Account Number (Optional)
    const bankAccountNo = document.getElementById('bank_account_no').value.trim();
    if (bankAccountNo.length > 0 && !/^\d+$/.test(bankAccountNo)) {
        document.getElementById('bank_account_noerror').textContent = 'Bank account number must contain only digits.';
        valid = false;
    }

    // Prevent form submission if invalid
    if (!valid) {
        event.preventDefault();
    }
});
