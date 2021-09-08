# Server-side request forgery (SSRF)

## What is Server-side request forgery ?

SSRF là 1 lỗ hổng bảo mật web cho phéo kẻ tấn công điều khiển ứng dụng phía máy chủ thực hiện các yêu cầu HTTP đến 1 tên miền tùy ý của hacker.

Trong 1 cuộc tấn công SSRF điển hình thì hacker có thể kiến server tạo kết nối với các dịch vụ chỉ dành cho nội bộ trong cở sở hạ tầng của tổ chức. Trong các trường họp khác thì có thể buộc máy chủ kết nối với các hệ thống bên ngoài, có thể làm rõ rỉ dữ liệu nhạy cảm,..

Thông thường, SSRF lcasc cuộc tấn công nhắm tục tiêu là các hệ thống nội bộ phía sau tường lửa mà thông thường không thể tiếp cận với bên ngoài(nhưng có thể thông qua 1 server để tiếp cận).

Sử dụng các cuộc tấn công SSRF nó có thể:

   + Quét hệ thống và tấn công từ mạng nội bộ mà không phải là truy cập bình thường
   
   + Liệt kê và các dịch vụ đang chạy trên các máy chủ tấn công
   
   + Khai thác dịch vụ chứng thực dựa trên máy chủ

## Impact of SSRF attacks

SSRF thành công thường có thể đẫn đến các hành động truy cập trái phép vào dữ liệu trong tổ chúc, trong chính ứng dụng dễ bị tấn công hoặc trên các hệ thống back-end mà ứng dụng có thể giao tiếp. Trong 1 vài trường hợp, SSRF có thể cho phép hacker thực hiện các câu lệnh tùy ý.

Việc khai thác SSRF gây ra kết nối với các hệ thống của bên thứ 3 bên ngoài có thể dẫn đến các cuộc tấn công nguy hiểm có vẻ như bắt nguồn từ tổ chức lưu trữ ứng dụng có lỗ hổng bảo mật.



## Type of SSRF attack

SSRF attacks against the server itself( Tấn công SSRF tấn công lại chính máy chủ): Hacker khiến ứng dụng thực hiện 1 yêu cầu HTTP quay lại máy chủ đang lưu trữ ứng dụng, thông qua giao diện mạng vòng lặp của nó. Điều này thường liên quan đến việc cung cấp URL có tên máy chủ như 127.0.0.1 hoặc localhost.

SSRF attacks against other back-end systems( Tấn công SSRF chống lại hệ thống back-end khác): Các hệ thống này thường có địa chỉ IP riêng không định tuyến được. Vì các hệ thống back-end thường được bảo vệ bởi cấu trúc liên kết mạng, chúng thường có vị trí bảo mật yếu hơn. Trong nhiều trường hợp, hệ thống back-end nội bộ chứa chức năng nhạy cảm có thể được truy cập mà không cần xác thực bởi bất kỳ ai có thể tương tác với hệ thống.

SSRF with blacklist-based input filters(SSRF với bộ lọc đầu vào dựa trên blacklist)

SSRF with whitelist-based input filters(SSRF với bộ lọc đầu vào dựa trên whitelist)

Bypassing SSRF filters via open redirection(Bỏ qua bộ lọc SSRF thông qua chuyển hướng): Với điều kiện API được sử dụng để thực hiện yêu cầu HTTP phía sau hỗ trợ chuyển hướng, bạn có thể tạo một URL đáp ứng bộ lọc và dẫn đến một yêu cầu được chuyển hướng đến mục tiêu phía sau mong muốn.



## Prevent SSRF attacks

Để ngăn ngừa các lỗ hổng SSRF trong ứng dụng web chúng ta có thể sử dụng whitelist, domains và protocols được phép truy cập tài nguyên máy chủ.

Nên tránh sử dụng các chức năng mà người dùng trực tiếp yêu cầu tài nguyên thay cho máy chủ. Ngoài ra bạn cũng nên filter input của người dùng bằng blacklist.
