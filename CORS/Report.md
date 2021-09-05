# Cross-origin resource sharing (CORS) là gì ?

## What is CORS ?
Tên đầy đủ là Cross-Origin Resource Sharing. Hiểu sâu hơn đó chính là chia sẻ tài nguyên có nhiều nguồn gốc khác nhau. Chính sách nguồn 
gốc giống nhau của trình duyệt là một cơ chế bảo mật quan trọng. Khách hàng từ các nguồn khác nhau không thể truy cập tài nguyên của nhau 
nếu không được phép. Định nghĩa của tương đồng là protocol,domain và port của liên kết truy cập là giống nhau. Trong các ứng dụng thực tế, 
các yêu cầu hợp lý giữa nhiều miền cũng rất quan trọng đối với một số ứng dụng. 

## Same-Origin Policy (Cùng nguồn gốc)

Same-origin policy là khi một website thực thi một cái gì đó. Nghĩa là, theo mặc định, ứng dụng web sử dụng API chỉ có thể yêu cầu tài nguyên 
HTTP từ cùng một nguồn gốc. Ví dụ, https://www.mywebsite.com yêu cầu https://www.mywebsite.com/page không có vấn đề gì. Nhưng khi tài nguyên 
được đặt trên các trang web có protocol , domain phụ hoặc port khác nhau , thì đó là một yêu cầu không cùng nguồn gốc mà hay các devjs chúng ta 
thường gọi là tên miền chéo.

Vậy, tại sao lại có same-origin policy tồn tại? Giả sử rằng chính sách cùng nguồn gốc không tồn tại và bạn đã vô tình nhấp vào một trong nhiều liên 
kết có virutss mà bạn của bạn gửi cho bạn trên Facebook. Liên kết này chuyển hướng bạn đến một "trang web xấu" có nhúng iframe tải trang web của ngân 
hàng của bạn và đăng nhập thành công cho bạn bằng một số cookie đã đặt! 😬 Sau khi đăng nhập thành công, trang web lừa đảo này còn có thể kiểm soát DOM 
của iframe và chuyển tiền trong thẻ của bạn thông qua một loạt thao tác thần sầu mà bạn không hề biết được.

## CORS HTTP headers 

- CORS là một cơ chế xác nhận thông qua Header của request. Cụ thể là trên Server sẽ nói với browser về quy định chấp nhận những request từ domain nào và phương 
thức ra sao (GET, POST, PUT, v.v..)

  + Access-Control-Allow-Origin: Những origin mà server cho phép. (ví dụ server loda.his chỉ chấp nhận loda.me request lên)

  + Access-Control-Allow-Headers: Những Header được server cho phép. (ví dụ x-authorize, origin, cái này do server bạn quy định)

  + Access-Control-Allow-Methods: Những Method được servẻ cho phép (POST, GET, v.v..)
  
 - Preflight request:

Một cái preflight request là một request được gửi từ phía trình duyệt để thăm dò xem server có hiểu/ hỗ trợ giao thức CORS hay không. Nó được tự động gởi bởi 
trình duyệt. Việc của phía server là trả về những headers cần thiết cho phía client.

  + Origin: domain hiện tại

  + Access-Control-Request-Method: Gửi lên các method để kiểm tra xem server có accept không. (GET, POST, v.v..)

  + Access-Control-Request-Headers: thăm dò xem một header nào đó có được chấp nhận không.
  
## Cơ chế hoạt động của CORS như thế nào?

- Trường hợp đơn giản nhất, client (web app chạy ở browser) sẽ tạo request GET, POST, PUT, HEAD, etc để yêu cầu server làm một việc gì đó. Những request này sẽ được đính 
kèm một header tên là Origin để chỉ định origin của client code (giá trị của header này chính là domain của trang web).

- Server sẽ xem xét Origin để biết được nguồn này có phải là nguồn hợp lệ hay không. Nếu hợp lệ, server sẽ trả về response kèm với header Access-Control-Allow-Origin.
Header này sẽ cho biết xem client có phải là nguồn hợp lệ để browser tiếp tục thực hiện quá trình request.

- Trong trường hợp thông thường, Access-Control-Allow-Origin sẽ có giá trị giống như Origin, một số trường hợp giá trị của Access-Control-Allow-Origin sẽ nhìn giống giống 
như Regex hay chỉ đơn giản là *, tuy nhiên thì cách dùng * thường được coi là không an toàn, ngoại trừ trường hợp API của bạn được public hoàn toàn và ai cũng có thể truy 
cập được.

==> Và như thế, nếu không có header Access-Control-Allow-Origin hoặc giá trị của nó không hợp lệ thì browser sẽ không cho phép
  
### Ví dụ : 

![gif]()
![gif]()

## Ngăn chặn các cuộc tấn công CORS
Các lỗ hổng CORS chủ yếu phát sinh do cách thiếp lập không đúng cách hoặc có sai sót.

  + Proper configuration of cross-domain requests(Cấu hình thích hợp của các yêu cầu tên miền chéo) : Nếu tài nguyên web chứa thông tin "nhạy cảm", nguồn gốc web phải được 
  chỉ định chính xác trong tiêu đề Access-Control-Allow-Origin.
  
  + Only allow trusted sites(Chỉ cho phép những trang web tin cậy): Các nguồn gốc được chỉ định trong tiêu đề Access-Control-Allow-Origin chỉ nên là các trang web đáng tin cậy.
  Đặc biệt, nguồn gốc phản ánh động từ các yêu cầu tên miền chéo mà không cần xác thực có thể dễ dàng khai thác và nên tránh.
  
  + Avoid whitelisting null(Tránh sử dụng liệt kê null): Tránh sử dụng tiêu đề Access-Control-Allow-Origin: null
  
  + Avoid wildcards in internal networks(Tránh các ký tự đại diện trong mạng nội bộ): Chỉ tin cậy cấu hình mạng để bảo vệ tài nguyên bên trong là không đủ khi các trình duyệt nội 
  bộ có thể truy cập các miền bên ngoài không đáng tin cậy.
  
  
## CORS Lab

### Lab: CORS vulnerability with basic origin reflection

Bài lab đã mô tả là trang web này có cấu hình CORS không an toàn ở chỗ nó tin cậy tất cả các nguồn gốc.

Để giải quyết bài lab, hãy tạo một số JavaScript sử dụng CORS để truy xuất khóa API của quản trị viên và tải mã lên máy chủ khai thác của bạn. 
Lab được giải quyết khi bạn gửi thành công khóa API của quản trị viên.

Bạn có thể đăng nhập vào tài khoản của mình bằng thông tin đăng nhập sau: wiener: peter
  
  
  
  
  
  
  
  
  
  
  
  
