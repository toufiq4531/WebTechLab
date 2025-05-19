function validate() {
  let valid = true;

  let fname = document.getElementById('fname').value;
  let nameRegex = /^[a-zA-Z.\- ]+$/;
  if (!nameRegex.test(fname) || /\d/.test(fname)) {
    document.getElementById('error').innerText = "Name can contain only letters, . and -, not numbers.";
    return valid = false;
  }

  let email = document.getElementById('email').value;
  let emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail|hotmail|yahoo)\.com$/;
  if (!emailRegex.test(email)) {
    document.getElementById('error').innerText = "Only Gmail, Hotmail or Yahoo allowed.";
    return valid = false;
  }


  let genderMale = document.getElementById('gender_male').checked;
  let genderFemale = document.getElementById('gender_female').checked;
  if (!genderMale && !genderFemale) {
    document.getElementById('error').innerText = "Please select a gender.";
    return valid = false;
  }


  let pass = document.getElementById('pass').value;
  let pass2 = document.getElementById('pass2').value;
  let passRegex = /^(?=.*[a-zA-Z])(?=.*\d).+$/;
  if (!passRegex.test(pass)) {
    document.getElementById('error').innerText = "Password must contain both letters and numbers.";
    return valid = false;
  }
  else if (pass !== pass2) {
    document.getElementById('error').innerText = "Password Doesn't Match";
    return valid = false;
  }


  let dob = document.getElementById('DOB').value;
  if (!dob) {
    document.getElementById('error').innerText = "Please enter your Date of Birth.";
    return valid = false;
  } else {
    let birthDate = new Date(dob);
    let age = new Date().getFullYear() - birthDate.getFullYear();

    if (age < 18) {
      document.getElementById('error').innerText = "You must be at least 18 years old.";
      return valid = false;
    }
  }


  let country = document.getElementById('country').value;
  if (!country) {
    document.getElementById('countryError').innerText = "Please select your country.";
    return valid = false;
  }


  let terms = document.getElementById('terms').checked;
  if (!terms) {
    document.getElementById('error').innerText = "You must accept the Terms and Conditions.";
    return valid = false;
  }

  return valid;
}

function signInValidate() {
  let username = document.getElementById('username').value.trim();
  let password = document.getElementById('signin_pass').value.trim();

  //credentials (username: "Pranto", password: "test123")
  if (username == "Pranto" && password == "test123") {
    window.location.href = "request.php";
    return false;
  } else {
    document.getElementById('error2').innerText = "Invalid username or password.";
    return false;
  }
}