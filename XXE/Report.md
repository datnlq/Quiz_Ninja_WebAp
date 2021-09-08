# XML external entity injection (XXE)


## What is XML enternal entity injection ?
 
XML entermal entity injection hay còn được gọi là XXE injection là 1 lỗ hổng được đánh giá mức độ nghiêm trọng là 4/10 Web Application Secirity Risks của OWASP.
XXE là một lỗ hổng bảo mật web cho phép hacker tấn công can thiệp vào quá trình xử lí dữ liệu XML của application.

Về XXE thì sẽ có 2 phần định nghĩa như sau : 
  
  + XML : là ngôn ngữ đánh dấu mở rộng được thiết kế với mục đích luuw trữ truyền dữ liệu và cả người và "máy" đều có thể đọc được.

Struct XML:
```
<?xml version="1.0" encoding="UTF-8"?>
  <application>
      <title>eMB</title>
      <company>MB</company>
      <year>2021</year>
      <price>40000000</price>
  </application>
```
Dòng đầu tiên là dùng để khai báo XML ( XML declaration)<Không bắt buộc>
Phần thân bao gồm nhiều cặp thẻ khác nhau tạo nên các phần tử khác nhau và lồng vào nhau tạo thành cấu trúc dạng cây. Sẽ có quy định về cú pháp, cách khai báo, cách lồng các phần tử, thuộc tính ,....

  + External Entity : entity tham chiếu đến nội dung một file bên ngoài tài liệu XML.
  
      *Entity : là 1 khái niệm có thể dược sử dụng như một kiểu tham chiếu đến dữ liệu, cho phép thay thế 1 ký tự đặc biệt,
      1 khối văn bản hay thậm chí toàn bộ nội dung 1 file vào trong tài liệu XML. Một số kiểu entity: character, parameter, named (internal), external…

```
<!DOCTYPE order SYSTEM "order.dtd">
<!DOCTYPE ran SYSTEM "/dev/random">
<!DOCTYPE request [
     <!ENTITY include SYSTEM "c:\secret.txt">
]>
```

==> XXE là lỗ hổng lợi dụng tính năng phân tích cú pháp của XML dùng để phân tích cú pháp đầu vào XML từ người dùng.
Dựa vào đó hacker có thể truy cập các tệp cục bộ, chạy các lệnh, quét các dịch vụ và các cổng nội bộ, truy cập mạng nội bộ, từ đó thưc hiện nhiều phương pháp tấn công tương ứng.

## How do XXE vulnerabilities arise?

Lỗi XXE phát sinh do bên trong XML có chứa các tính năng nguy hiểm và XML cho phép sử dụng các công cụ phân tích các tính năng này.

## What are the types of XXE attacks?

  + Exploit XXE to retireve files(Khai thác XXE để truy xuất file) :  External entity được xác định có chứa nội dung của file và trả về trong phản hồi của ứng dụng.
  
  + Exploit XXE to perform SSRF attacks(Khai thác XXE để thực hiện các cuộc tấn công SSRF) : External entity được xác định dựa trên URL đến hệ thống back-end.
  
  + Exploit blind XXE exfiltrate data out-of-band(Khai thác lỗ hổng XXE mù ngoài luồng dữ liệu exfiltrate): Dữ liệu nhạy cảm có thể được truyền từ máy chủ ứng dụng đến máy chủ của hacker.
  
  + Exploit blind XXE to retire data via error messages(Khai thác lỗ hổng XXE mù để truy xuất dữ liệu thông qua thông báo lỗi): Nơi mà hacker dùng các thông báo lỗi để phân tích dữ liệu nhạy cảm.
  




## XXE attack flow

Hacker có thể tận dụng tối đa các thực thể bên ngoài XML để sử dụng lổ hổng này nhằm sử dụng chức năng bên ngoài của nó.
Trong nhiều trường hợp thì lỗ hổng XXE cũng có thể là 1 ví dụ về cách hacker có thể tận dụng cấu hình sai này của trình phân tích cú pháo XML về cơ bản biến nó thành 1 máy chủ proxy để chúng có thể thực hiện các cuộc tấn công Select query về phía máy chủ 
và truy cập vào mạng nội bộ hoặc có thể kết nối với các máy chủ công cộng bên ngoài từ sau tường lửa. Hacker có thể sử dụng định nghĩa thực thể XML và định danh hệ thống trên trình phân tích cú pháp XML để chỉ chấp nhận các yêu cầu được tạo ra thủ công độc hại chứa các tệp XML dường như vô hại đối với tường lửa hoặc ứng dụng vì chức năng của các dịch vụ đó dường như không bị tấn công trực tiếp.

## Find and test for XXE vulnerabilities

Đa số các lỗ hổng XXE thì chúng ta có thể tìm được thông qua chức năng scanner vulnerabilities của Burp Suite hỗ trợ.

Ngoài ra có thể kiểm tra thủ công như sau :

  + Kiểm tra khả năng truy xuất tệp bằng cashc xác định một external entity dựa trên một tệp hệ điều hành nổi tiếng và sử dụng thực thể đó trong dữ liệu được trả về trong phản hồi của ứng dụng
  
  + Kiểm tra các lỗ hổng blind XXE bằng cashc xác định 1 external entity bên dựa trên URL của 1 hệ thống mà bạn kiểm soát và giám sát. Chức năng Burp Collaborator của Burp Suite có thể hỗ trợ chúng ta phần này.
  
  + Kiểm tra xem máy có dễ bị injection vào dữ liệu không phải là XML do người dùng cung cấp trong tài liệu XML phía máy chủ hay không, bằng cashc sử dụng XInclude attack để cố gắng truy xuất hệ điều hành nổi tiếng.
  
*XInclude* là một phần của đặc tả XML cho phép một tài liệu XML được xây dựng từ các tài liệu con. Bạn có thể đặt một cuộc tấn công XInclude trong bất kỳ giá trị dữ liệu nào trong tài liệu XML, do đó, cuộc tấn công có thể được thực hiện trong các tình huống mà bạn chỉ kiểm soát một mục dữ liệu duy nhất được đặt vào tài liệu XML phía máy chủ.

Để thực hiện một cuộc tấn công XInclude, bạn cần tham chiếu không gian tên XInclude và cung cấp đường dẫn đến tệp mà bạn muốn đưa vào. Ví dụ:

```
<foo xmlns: xi = "http://www.w3.org/2001/XInclude">
<xi: include parse = "text" href = "file: /// etc / passwd" /> </foo>

```

### Prevent XXE vulnerabilities

Các lỗ hổng XXE đều phát sinh do thư viện phân tích cú pháp XML của ứng dụng hỗ trợ các tính năng XML tiềm ẩn nguy hiểm ma fungws dụng không cần hoặc có ý định sử dụng. CÁch dễ nhất và hiệu quả nhất là vô hiệu hóa những chức năng tiềm ẩn nguy hiểm đó.

  + Bất cứ khi nào có thể, hãy sử dụng các định dạng dữ liệu ít phức tạp hơn như JSON và tránh tuần tự hóa dữ liệu nhạy cảm.

  + Vá hoặc nâng cấp tất cả các bộ xử lý và thư viện XML được ứng dụng hoặc trên hệ điều hành cơ bản sử dụng. Sử dụng bộ kiểm tra phụ thuộc. Cập nhật SOAP lên SOAP 1.2 hoặc cao hơn.
  
  + Triển khai xác thực, lọc hoặc khử trùng đầu vào tích cực phía máy chủ để ngăn chặn dữ liệu thù địch trong các tài liệu, tiêu đề hoặc nút XML.

  + Xác minh rằng chức năng tải lên tệp XML hoặc XSL kiểm tra XML đến bằng cách sử dụng xác thực XSD hoặc tương tự.

  + Các công cụ Kiểm tra Bảo mật Ứng dụng Tĩnh có thể phát hiện XXE trong mã nguồn, nhưng xem xét mã thủ công là giải pháp thay thế tốt nhất trong các ứng dụng phức tạp với nhiều tích hợp.



## XXE Lab


### 





































