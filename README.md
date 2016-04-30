# Simple-AntiCSRF-Class
Simple PHP Anti CSRF Class

# How To Use 

Put Token::token_view($key); Middle Of Your Form and $key must be your form specific name

Sample :
```
<form method="post" action="www.domain.dev/register">

<input type="name" placeholder="Name">

<?php Token::token_view("register"); ?>

<input type="submit" name="register" value="Regsiter">

</form>
```
After That You Must Check Token Before Proccessing Form by Token::token_validation($key);

Sample 
```  
if(isset($_POST['register'])){

  if(Token::token_validation("register")){
  
    // Do Stuff
  
  }else{
  
    print 'Wrong Token !';
  
  }

}
```
