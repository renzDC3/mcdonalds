<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="#.css">
    <link rel="stylesheet" href="style.css">
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="loadLayout.js" defer>
      var form = $('#contact'),
    submit = form.find('[name="submit"]');

form.on('submit', function(e) {
  e.preventDefault();
  
  // avoid spamming buttons
  if (submit.attr('value') !== 'Send')
    return;
  
  var valid = true;
  form.find('input, textarea').removeClass('invalid').each(function() {
    if (!this.value) {
      $(this).addClass('invalid');
      valid = false;
    }
  });
  
  if (!valid) {
    form.animate({left: '-3em'},  50)
        .animate({left:  '3em'}, 100)
        .animate({left:    '0'},  50);
  } else {
    submit.attr('value', 'Sending...')
          .css({boxShadow: '0 0 200em 200em rgba(225, 225, 225, 0.6)',
                backgroundColor: '#ccc'});
    // simulate AJAX response
    setTimeout(function() {
      // step 1: slide labels and inputs
      form.find('label')
          .animate({left: '100%'}, 500)
          .animate({opacity: '0'}, 500);
    }, 1000);
    setTimeout(function() {
      // step 2: show thank you message after step 1
      submit.attr('value', 'Thank you :)')
            .css({boxShadow: 'none'});
    }, 2000);
    setTimeout(function() {
      // step 3: reset
      form.find('input, textarea').val('');
      form.find('label')
          .css({left: '0'})
          .animate({opacity: '1'}, 500);
      submit.attr('value', 'Send')
            .css({backgroundColor: ''});
    }, 4000);
  }
});

       
    </script>
    

    <title></title>
</head>
<style>


}
html {
    font: 300 100%/1.5 Ubuntu, sans-serif;
    color: #333;
    overflow-x: hidden;
}
h1{text-align:center;
  margin:auto;
  font-size:large;
  }

h2 {
    margin: 20;
    color: #8495a5;
    font-size: 2.5em;
    font-weight: 300;
}

#contact-form {
    max-width: 1208px;
    max-width: 75.5rem;
    margin: 0 auto;
}

#contact, label, input[name="submit"] {
    position: relative;
}

label > span, input, textarea, button {
    box-sizing: border-box;
}

label {
    display: block;
}

label > span {
    display: none;
}

input, textarea, button {
    width: 100%;
    padding: 0.5em;
    border: none;
    font: 300 100%/1.2 Ubuntu, sans-serif;
}

input[type="text"], input[type="email"], textarea {
    margin: 0 0 1em;
    border: 1px solid #ccc;
    outline: none;
}

input.invalid, textarea.invalid {
    border-color: #d5144d;
}

textarea {
    height: 6em;
}

input[type="submit"], button {
    background: #a7cd80;
    color: #333;
}

input[type="submit"]:hover, button:hover {
    background: #91b36f;
}

@media screen and (min-width: 30em) {
    #contact-form h2 {
        margin-left: 26.3736%;
        font-size: 2em;
        line-height: 1.5;
    }

    label > span {
        vertical-align: top;
        display: inline-block;
        width: 26.3736%;
        padding: 0.5em;
        border: 1px solid transparent;
        text-align: right;
    }

    input, textarea, button {
        width: 73.6263%;
        line-height: 1.5;
    }

    textarea {
        height: 10em;
    }

    input[type="submit"], button {
        margin-left: 26.3736%;
    }
}

@media screen and (min-width: 48em) {
    #contact-form {
        text-align: justify;
        line-height: 0;
    }

    #contact-form:after {
        content: '';
        display: inline-block;
        width: 100%;
    }

    #contact-form h2 {
        margin-left: 17.2661%;
    }

    #contact-form form, #contact-form aside {
        vertical-align: top;
        display: inline-block;
        width: 65.4676%;
        text-align: left;
        line-height: 1.5;
    }

    #contact-form aside {
        width: 30.9353%;
    }
}

</style>

<body>
    <section id="contact-form">
        <h2>Contact</h2>
        <form id="contact" name="contact" accept-charset="utf-8">
            <label><span>Name</span><input name="name" type="text" placeholder="Name"/></label>
            <label><span>Email</span><input name="email" type="email" placeholder="Email"/></label>
            <label><span>Message</span><textarea name="message" placeholder="Message"></textarea></label>
            <input name="submit" type="submit" value="Send"/>
        </form>
        <aside>
            <p>give us a feedback.</p>
            <p></p>
        </aside>
    </section>
   
</body>

</html>




