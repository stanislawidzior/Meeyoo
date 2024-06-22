<h1>Register</h1>
<p>Already a member? <a href="main.php?goTo=login">Log in</a></p>
<form action="main.php" method="POST" id="registrationForm">
        <input type="text" hidden id="type" name="type" value="register">
        <label for="name">Your name</label>
        <input type="text" placeholder = name id="name" name="name" >
        <label for="surname">Your surname</label>
        <input type="text" placeholder = surname id ="surname" name="surname" >
        <label for="email">Your E-mail</label>
        <input type="text" placeholder="e-mail" id="example@email.com" name="email" >
        <label for="name">Your age</label>
        <input type="text" placeholder = "age" id ="age" name="age" pattern="[0-9]{1-3}" title="Insert a numeric value" >
        <label for="gender">Your gender</label>
        <select name="gender" id="gender" >
            <option value="rather not say">none</option>
            <option value="male">male</option>
            <option value="female">female</option>
            <option value="custom">custom</option>
        </select>
        <label for="customGender" id="customGenderLabel" style="display:none;">Please specify:</label>
        <input type="text" id="customGender" name="customGender" placeholder="Enter your gender">
        <label for="password">Your password</label>
        <input type="password" placeholder="password" id="password" name="password"
        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,64}" 
        title="Password must be at least 8 characters long and contain at least one number, one uppercase letter, one lowercase letter, and one special character." >
        <label for="password">Repeat your password</label>
        <input type="password" placeholder="password" id="passwordRepeat" name="passwordRepeat" >
        <input type="submit" value="Register" id="RegisterButton">
    </form>
    <?php
    if(isset($_SESSION["registerError"])){
       if($_SESSION["registerError"] == "none"){
        echo '<p id="inputSuccess"> Register succseful </p>';
       }else{
        echo '<p id="inputError">Error occured: '.$_SESSION["registerError"].'</p>';
    }
    unset($_SESSION["registerError"]);
}
    ?>
    <script>
       
    
        document.getElementById('gender').addEventListener('change', function() {
            var customGenderInput = document.getElementById('customGender');
            var customGenderLabel = document.getElementById('customGenderLabel');
            if (this.value === 'custom') {
                customGenderInput.style.display = 'inline';
                customGenderLabel.style.display = 'inline';
            } else {
                customGenderInput.style.display = 'none';
                customGenderLabel.style.display = 'none';
            }
        });
        var genderSelect = document.getElementById('gender');
        var customGenderInput = document.getElementById('customGender');
            var customGenderLabel = document.getElementById('customGenderLabel');
            if (gender.value === 'custom') {
                customGenderInput.style.display = 'inline';
                customGenderLabel.style.display = 'inline';
            } else {
                customGenderInput.style.display = 'none';
                customGenderLabel.style.display = 'none';
            }
        
       
        document.getElementById('passwordRepeat').addEventListener('input', function() {
            var password = document.getElementById('password').value;
            var passwordRepeat = this.value;

            if (password !== passwordRepeat) {
                this.classList.add('error');
                document.getElementById('password').classList.add('error');
            } else {
                this.classList.remove('error');
                document.getElementById('password').classList.remove('error');
            }
        });

        function validateForm() {
        var name = document.getElementById('name').value.trim();
        var surname = document.getElementById('surname').value.trim();
        var email = document.getElementById('email').value.trim();
        var age = document.getElementById('age').value.trim();
        var gender = document.getElementById('gender').value;
        customGender = document.getElementById('customGender').value.trim();
        var customGender = '';
        if (gender === 'custom') {
            customGender = document.getElementById('customGender').value.trim();
        }
        if (name === '' || surname === '' || email === '' || age === ''  || (gender === 'custom' && customGender === '') || password === '' || passwordRepeat === '') {
            return false
        }
        return true;
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
            alert('Please fill out all required fields.');
        }
    });
    }
    </script>
    