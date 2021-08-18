# PortSwigger Web Security Academy Labs 

## SQL Injection
 
### [SQL injection vulnerability in WHERE clause allowing retrieval of hidden data]
Đề bài yêu cầu chúng ta khai thác lỗ thông trong phần filter danh mục sản phẩm, và đã cho sẳn chúng ta câu truy vấn sql như sau: 
```
SELECT * FROM products WHERE category = 'Gifts' AND released = 1
```
Khi truy cập vào bài lab, thì chúng ta thấy 1 trang web bán hàng gồm nhiều sản phẩm, theo như đề bài chúng ta filter bằng những từ khóa có sẳn dùng Burp Suite để bắt lấy request như sau:


Chuyển sang repeater để thử nhiều trường hợp. Để khai thác lỗ hổng này chúng ta dựa vào câu truy vấn đề đã cho sẳn, mình sẽ được khi vào phần category. Khai thác lỗ hổng này thì mình chèn vài dong truy vấn như sau: 
```
SELECT * FROM products WHERE category = 'Gifts' OR 1=1 -- AND released = 1
```

Câu truy vấn trên có nghĩa là phần check released = 1 đã bị bỏ đi vì '--' là cmt trong ngôn ngữ sql, server sẽ hiểu phần sau là 1 câu cmt và điều kiện 1=1 luôn luôn đúng nên tất cả các dữ liệu sẽ bị leak ra .

### [SQL injection vulnerability allowing login bypass]

Mô tả bài lab cho chúng ta thấy đây chúng ta sẽ khai thác 1 lỗ hổng đăng nhập có username = administrator. Và bây giờ chúng ta truy cập bài lab.



Đề bài cho chúng ta 1 trang web bán hàng, để ý thấy phần account theo như mô tả, chúng ta vào và login.

Sau khi dùng burp suite bắt lại request login chúng ta thấy như sau: 


Từ đó có thể đoán được cây truy vấn có thể có dạng :
```
SELECT * FROM products WHERE username = 'administrator' AND password = 'hello'
```

Vậy vì username đã biết nên chúng ta chỉ việc bypass qua phần password là sẽ solve được lab này. Việc dùng '--' để bypass sẽ giúp chúng ta biến phần check pass trở thành 1 câu cmt và chúng ta đã qua được.



### SQL injection UNION attack, determining the number of columns returned by the query

Bài lab mô tả rằng chúng ta sẽ khai thác lỗ hổng trong phần danh mục sản phẩm bằng câu lệnh *UNION*. 


TRuy cập bài lab chúng ta thấy lại page bán hàng lúc nãy và lọc thử category dùng BurpSuite bắt lại request để phân tích.

Theo như request thì câu query vẫn sẽ là : 
```
SELECT * FROM products WHERE category = 'Gifts' AND released = 1
```
Để áp dụng *UNION* và thì có cách như sau :
```
Phương pháp thứ hai liên quan đến việc gửi một loạt các tải trọng chỉ định một số giá trị null khác nhau: UNION SELECT

' UNION SELECT NULL--
' UNION SELECT NULL,NULL--
' UNION SELECT NULL,NULL,NULL--
```
Phương pháp này sẽ bypass được nếu số lần NULL = số cột trong database của server.
Để check được số cột chúng ta dùng repeater để check.


Và kết quả thu được là có 3 cột và chúng ta đã exploit thành công.


### Lab: SQL injection UNION attack, finding a column containing text

Tên bài lab là tìm 1 cột chứa văn bản , :vv khả năng bruteforce khá cảo. Theo mô tả bài lab chúng ta vẫn sẽ khai thác ở phần category của trang web bán hàng. Dựa trên thông tin kỹ thuật của việc tìm văn bản thì chúng ta phải xác định số cột trước và sau đó mới có thể kiểm tra và xác định văn bản.

Sau khi thử 3 lần thì chúng ta xác định được có 3 cột và tương tự chúng ta sẽ tìm text theo phương thức sau : 
```
Sau khi đã xác định số lượng cột cần thiết, bạn có thể thăm dò từng cột để kiểm tra xem nó có thể giữ dữ liệu chuỗi hay không bằng cách gửi một loạt các tải trọng đặt giá trị chuỗi vào mỗi cột lần lượt. Ví dụ: nếu truy vấn trả về 3 cột, bạn sẽ gửi: UNION SELECT

' UNION SELECT 'a',NULL,NULL--
' UNION SELECT NULL,'a',NULL--
' UNION SELECT NULL,NULL,'a'--

```
Sau khi check 3 lần thì chúng ta xác định được cột thứ 2 là text và cũng đã bypass thành công. Và chúng ta đã có được chuỗi text mà trang web trả về như sau.


Sau đó thay thế string vào chữ a mà chúng ta đã thử để hoàn thành bài lab


### SQL injection UNION attack, retrieving data from other tables

Mô tả của bài lab này nói rằng chũng ta vẫn sẽ khai thác lỗ hổng ở phần category tuy nhiên chúng ta phải kết hợp các kỹ thuật ở bài trước như dùng UNION để check số column, check text, và UNION 1 câu lệnh khác kèm theo bypass phần đuôi, bla bla 

Nghe nó như 1 bài tổng hợp vậy. Giờ chúng ta vào page bán hàng và bắt request để testing lỗ hổng nào.


Đầu tiên chúng ta sẽ dùng *UNION SELECT NULL--* để check xem có bao nhiêu column ở đây.



Sau khi check được 2 column chúng ta sẽ xác định xem 2 column này có phải text hay không vì chúng ta đang muốn leak ra username và password.


Sau đó chúng ta sẽ dùng câu truy vấn sau để leak: 
```
SELECT * FROM products WHERE category = 'Gifts' UNION SELECT username, password FROM users -- AND released = 1
```
Và chúng ta điều chỉnh bằng burpsuite khi bắt request như sau: 


Chúng ta đã có password và username. ==> Đăng nhập để solve bài lab.

