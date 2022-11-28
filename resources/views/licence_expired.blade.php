<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Qbh-Ebill: Licence Expired</title>

    <style type="text/css">
        body {
  background: rgba(96, 196, 196, .3);
  font-family: 'Open-sans', sans-serif;
}
.expired {

}
svg {
  width: 30%;
  margin: 0 5% 3vh !important;
}

.st0{fill:#EFCBB4;}
.st1{fill:#FFE1CA;}
.st2{fill:#473427;}
.st3{
    fill:none;
    stroke:#473427;
    stroke-width:7;
    stroke-linecap:round;
    stroke-miterlimit:10;
}
.st4{fill:#D37D42;stroke:#D37D42;stroke-miterlimit:10;}

.smile {
  display: none;
}
.uhoh {
  display: none;
}
path.smile {
    fill-opacity: 0;
    stroke: #000;
    stroke-width: 6;
    stroke-dasharray: 870;
    stroke-dashoffset: 870;
    animation: draw 7s infinite linear;
  }
@keyframes draw {
  to {
    stroke-dashoffset: 0;
  }
}
#path {
  stroke-dasharray: 628.3185307179587;
  animation: dash 5s linear forwards;
}
@keyframes dash {
  from {
    stroke-dashoffset: 628.3185307179587;
  }
  to {
    stroke-dashoffset: 0;
  }
}

.message {
  text-align: left;
  margin: 5em 5em;
  padding: 0 2em;
}
.message h1 {
  color: #3698DC;
  font-size: 3vw !important;
  font-weight: 400;
}
.message p {
  color: #262C34;
  font-size: 1.3em;
  font-weight: lighter;
  line-height: 1.1em;
}
.light {
  position: relative;
  top: -36em;
}
.light_btm {
  position: relative;
}
.light span:first-child {
  display: block;
  height: 6px;
  width: 150px;
  position: absolute;
  top:5em;
  left: 20em;
  background: #fff;
  border-radius: 3px;
/*   transform: rotate(40deg); */
}
.light span:nth-child(2) {
  display: block;
  height: 6px;
  width: 200px;
  position: absolute;
  top:6em;
  left: 19em;
  background: #fff;
  border-radius: 3px;
/*   transform: rotate(40deg); */
}
.light span:nth-child(3) {
  display: block;
  height: 6px;
  width: 100px;
  position: absolute;
  top:7em;
  left: 24em;
  background: #fff;
  border-radius: 3px;
/*   transform: rotate(40deg); */
}

.light_btm span:first-child {
  display: block;
  height: 6px;
  width: 150px;
  position: absolute;
  bottom:6em;
  right: 20em;
  background: #fff;
  border-radius: 3px;
/*   transform: rotate(40deg); */
}
.light_btm span:nth-child(2) {
  display: block;
  height: 6px;
  width: 200px;
  position: absolute;
  bottom: 7em;
  right: 21em;
  background: #fff;
  border-radius: 3px;
/*   transform: rotate(40deg); */
}
.light_btm span:nth-child(3) {
  display: block;
  height: 6px;
  width: 100px;
  position: absolute;
  bottom:8em;
  right: 24em;
  background: #fff;
  border-radius: 3px;
/*   transform: rotate(40deg); */
}
.use-desktop {
  font-weight: 400;
  color: #3698DC;
  border: 1px solid white;
  height: 3.4em;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  border-radius: 25px;
  line-height: 1.1em;
  font-size: 5vw;
}

img.logo{width: 150px}

</style>

</head>
<body>

<div class="message">
  <img src="{{ asset('build/img/crater-logo.png') }}" class="logo">
  <h1>
    <b>Renew Your Licence</b>
    <br>تجديد رخصتك
  </h1>

  <h2>
    Your software subscription or licence is expired!
    <br>
    انتهت صلاحية اشتراكك أو ترخيصك في البرنامج
  </h2>
  <p>
    Please contact admin to renew or active services
    <br>
    <span>
      يرجى الاتصال بالمسؤول للتجديد أو الخدمات النشطة
    </span>
  </p>
  <a href="tel:+1328888888888">+132 8888 888 888</a>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    var $elem = $(".smile"),
  l = $elem.length,
  i = 0;
var $elem2 = $(".uhoh"),
  l = $elem2.length,
  i = 0;

var $elem = $(".smile");
var $elem2 = $(".uhoh");

function comeOn() {
  for (var i = 0; i < 3; i++) {
    if (i % 2) {
      $elem.fadeIn(700);
      // $elem2.fadeOut(700);
    } else {
      $elem.fadeOut(700);
      $elem2.hide().delay(700).fadeIn(700);
    }
  }
}
comeOn();

</script>


<script type="module" setup>
  import axios from './axios'

</script>

</body>
</html>