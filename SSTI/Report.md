# Server-side template injection 

## What is Server-side template injection (SSTI)

Template engines : là 1 công cụ giúp chúng ta tách HTML thành cách phần nhỏ hơn mà chúng ta có thể sử dụng lại trên nhiều tập tin HTML, được sử dụng bởi các ứng dụng web để trình bày dữ liệu thông qua web hoặc emails.

SSTI là 1 lỗ hổng nghiêm trọng thường bị nhầm lẫn với lỗ hổng XXSS, tuy nhiên SSTI sẽ tất công trực tiếp vào máy chủ và thường bao gồm Remote Code Execution (RCE) - thực thi mã từ xa

### Server-side template injection vulnerabilities arise

SSTI có thể phát sinh thông qua những lỗi thuộc về phía nhà phát triển vì đã không chú trọng về phẩn bảo mật hoặc những hành vi có chủ đích của template để cố gắng cung cấp những chức năng phong phú - thường được thực hiện bởi wikis, blogs, các ứng dụng tiếp thị và các hệ thống quản lý nội dung.


SSTI phát sinh khi input đầu vào của người dùng được nhúng vào các template thay vì được chuyển vào dưới dạng dữ liệu.

## Detect SSTI
### Plaintext context
- Lỗi thường sẽ xuất hiện theo một trong những phương thức sau:
```
smarty=Hello {user.name}
Hello user1
````

 Để phát hiện lỗi, chúng ta cần gọi template engine bằng cách nhúng một câu lệnh. Có một số lượng lớn các template languages nhưng phần lớn trong đó chia sẻ các đặc điểm cú pháp cơ bản. Chúng ta có thể tận dụng điều này bằng cách gửi các payload sử dụng các toán tử cơ bản để phát hiện nhiều template engine bằng 1 câu HTTP request duy nhất.

### Code context
- Dữ liệu đầu vào của người dùng cũng có thể được đặt trong một template statement, thường là tên biến
```
personal_greeting=username
Hello user01
````
Biến này thậm chí còn dễ bỏ sót hơn trong quá trình đánh giá, vì nó không dẫn đến XSS một cách rõ ràng. Thay đổi giá trị của username thường sẽ trả về kết quả rỗng hoặc gây lỗi ứng dụng. Trường hợp này có thể được phát hiện bằng cách xác minh các tham số không thể XSS trực tiếp, sau đó thoát ra khỏi template statement và thêm thẻ HTML vào sau nó :
```
personal_greeting=username<tag>
Hello
personal_greeting=username}}<tag>
Hello user01 <tag>
```

## Identify SSTI
Sau khi đã biết lỗi SSTI được phát sinh thì chúng ta phải xác định loại template engines được dùng, chúng ta có thể dựa vào hình sau đây để suy ra loại template tương ứng.



Sau khi tìm được loại template thì chúng ta có thể tham khảo các payload injection ở đây : https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/Server%20Side%20Template%20Injection#freemarker---basic-injection


## Exploit
### Read
Sau khi xác định được template engine, chúng ta cần xác định tiếp theo là :

   + Cú pháp cơ bản của template engine
   + 
   + Danh sách các phương thức, hàm, bộ lọc và biến đã được dựng sẵn
   + 
   + Danh sách các tiện ích mở rộng / plugin
### Explore
Ở bước này, việc chúng ta cần làm là tìm ra chính xác những gì có thể truy cập được.

  + Xem xét cả những objects mặc định được cung cấp bởi template engine lẫn các objects dành riêng cho ứng dụng được truyền vào template bởi các nhà phát triển.
  + 
  + Bruteforce các tên biến. Các object do nhà phát triển cung cấp có khả năng chứa các thông tin nhạy cảm.
### Attack
  - Xem xét từng function để tìm các lỗ hổng có thể khai thác. Các dạng tấn công có thể là tạo object tùy ý, đọc / ghi file tùy ý (bao gồm cả remote file), hay khai thác lỗ hổng leo thang đặc quyền.


## Example
Challenge của root me : http://challenge01.root-me.org/web-serveur/ch41/

Đọc source như sau : 
```
function checkSubmit(e) {
		if (e && e.keyCode == 13) {
			checkNickname();
		}
	}

	function checkNickname() {
		var serviceUrl = "check";
		var nick = $("#nickname").val();
		var postData = "nickname=" + encodeURIComponent(nick);
		$.ajax({
			url : serviceUrl,
			type : "POST",
			data : postData,
			contentType : "application/x-www-form-urlencoded",
			dataType : "text",
			success : function(data) {
				$("#result").text(data);
			},
			error : function(data) {
				$("#result").text("An error occurs!");
			}
		});
	}
  ```
  
- Hàm này sử dụng ajax trong jquery để gửi data từ client lên server:

  + url: serviceUrl (gửi data đến url: /web-serveur/ch41/check)
  + type : "POST" (dùng POST method)
  + data : postData (data được gửi đi nằm trong biến postData)

- Nếu request thành công thì nó sẽ trả lại dữ liệu trong "data" và đổ vào thẻ div có id là "result", ngược lại thì đưa ra thông báo lỗi "An error occurs!".

- Đầu tiên, cần phải xác định loại template engine được sử dụng:


=> Báo error như ảnh thì đoán engine được dùng có thể là FreeMarker

Xem các payload trong link sau để hiểu rõ hơn : https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/Server Side Template Injection
```
<#assign cmd = "freemarker.template.utility.Execute"?new()>${ cmd("id")}
[#assign cmd = 'freemarker.template.utility.Execute'?new()]${ cmd('id')}
${"freemarker.template.utility.Execute"?new()("id")}
```
[<#assign>](http://freemarker.org/docs/dgui_misc_var.html) cho phép định nghĩa biến ngay trong template . Đoạn code trên tạo một tên biến là "cmd", việc sử dụng Built-in "freemarker.template.utility.Execute"?new() cho phép tạo một object tùy ý, chính là object của "Excute" Class được implement từ "TemplateModel".

- Sử dụng payload trên để sửa nội dung input trong "nickname" khi bắt gói tin bằng Burp Suite:


