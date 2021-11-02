# Access Control Vulnerabilities 

Chào mọi người ~ Mình đã dừng việc traning trên portswigger 1 thời gian khá dài, maybe do mình chơi đồ (án) nhiều quá :3 Nhưng dù sao mình không nên bỏ bê chứng chỉ web này được :D.
Hôm nay chúng ta sẽ mô tả về lỗ hổng kiểm soát truy cập(Access control) và leo thang đặc quyền (Privilege escalation) nhé!
Để có thể khai thác được bất kỳ lỗ hổng nào thì chúng ta đều phải biết nó là lỗ hổng gì trước nên chúng ta sẽ tìm hiểu qua khái niệm của 2 lỗ hổng này trước


### What is access control ?
Access control ( authorization <ủy quyền>) là cấp quyền, ủy quyền, áp dụng các ràng buộc về ai đó hoặc gì đó để có thể thực hiện các hành động hoặc truy cập các tài nguyên.

Trong web app thì có các ngữ cảnh kiểm soát truy cập như sau : 

  + Authentication : Xác thực người dùng chính là họ
  + Session management xác định các yêu cầu HTTP tiếp theo được cho phép thực hiện bởi chính người dùng
  + Access control xác định liệu người dùng có được phép thực hiện hành động hoặc truy cập đó hay không.
  
 Lỗ hổng kiểm soát truy cập là 1 lỗ hổng thường gặp và khá nghiêm trọng. Việc thiết kế, quản lý các biện pháp kiểm soát quyền truy cập là một vấn đề phức tạp. Mà mô hình này được thiết kế bởi con người cho nên khả năng xảy ra sai sót là rất cao.

Việc hacker tấn công vào lỗ hổng này được chia làm 2 loại như sau được đề cập đến trong Top 10 lỗ hổng bảo mật web của OWASP-2017

#### Broken Authentication and Session Management
Khi các chức năng của ứng dụng được thực hiện không chính xác, hacker có thể dễ dàng xâm nhập, đánh cắp
thông tin tài khoản, mật khẩu và khai thác các lỗ hổng khác bằng cách sử dụng các chứng chỉ đã đánh cắp.
Tài khoản mỗi người dùng cá nhân nên là duy nhất, không bị trùng lặp dưới bất kì hình thức nào.
Nếu không có bất kì sự quản lý cần thiết nào thì tin tặc có thể lẻn vào, ngụy trang thành người dùng để 
ăn cắp thông tin tài khoản, mật khẩu và sử dụng cho những lần truy cập sau này.

#### Broken Access Control 
Khi người dùng bị hạn chế kiểm soát truy cập, hacker có thể khai thác và truy cập các chức năng hoặc dữ 
liệu trái phép. Kiểm soát truy cập nhằm mục đích kiểm soát người dùng trong một ứng dụng và để thiết lập
quyền kiểm soát truy cập một cách hợp lý, ứng dụng phải đảm bảo thực hiện kiểm tra ủy quyền, phân quyền và
xác thực hợp lệ để xác định rõ ràng đặc quyền của người dùng. Nguyên nhân dẫn đến lỗi này có thể do các nhà phát triển
gặp lỗi trong quá trình phân quyền và kiểm soát yêu cầu phù hợp với các quy tắc đặt ra, hoặc có thể do an ninh lỏng lẻo tại các tầng kiến trúc của web như
framework, server, serverdata, ...nên hacker có thể lợi dụng và tấn công lấy quyền truy cập.
### What is Privilege escalation ?
Privilege escalation ( leo thang đặc quyền) xảy ra khi bạn có thể truy cập những chức năng hoặc tài nguyên mà bạn không được cho phép, việc leo thang đặc quyền này cần phải được ngăn chặn. Leo thang đặc quyền thường xuất hiện do 1 lỗ hổng trong ứng dụng gây ra.
Mức độ leo thang tùy thuộc vào những đặc quyền mà hacker có được dựa vào khai thác lỗ hổng.

### The Impacts of Broken Access Control

Như đã đề cập đến, mức độ của lỗ hổng này tùy thuộc vào dữ liệu mà hacker có thể khai thác được. Một số hành vi đặc trưng như sau : 

###### Exposure of Unauthorized Content (Hiển thị nổi dung trái phép)
Khi hacker đã có đặc quyền truy cập trái phép thì sẽ có thể tiếp cận tới nhiều tài nguyên không được phép, điều đó có nghĩa là hacker sẽ có thể truy cập, tác động lên những thông tin nhạy cảm của công ty.
###### Privilege Escalation (Leo thang đặc quyền)
Hacker khai thác các lỗ hổng để có được đặc quyền cao hơn đặc quyền hiện có của mình, việc này có thể giúp hacker dễ dàng đánh cắp dữ liệu hoặc triển khai các payload độc hại.
###### Distributed Denial of Service ( Từ chối dịch vụ <DDos>)
Việc quyền truy cập vào nhiều tài khoản, hacker có thể triển khai tấn công bằng cách setup bot trong các tài khoản này để nó liên tục gửi các request đến server. Khiến không truy cập được các dich vụ, ...






#### Common Access Control Vulnerabilities (Các lỗ hổng kiểm soát truy cập phổ biến)

 - Vertical Privilege Escalation: 
 
 Ví dụ về các cuộc tấn công leo thang đặc quyền theo chiều dọc từ các kiểm soát truy cập theo chiều dọc bị hỏng bao gồm:

    + Chức năng nhạy cảm không được bảo vệ

    + Các cuộc tấn công dựa trên tham số

    + Kiểm soát truy cập bị hỏng do định cấu hình sai nền tảng
 
  - Horizontal Privilege Escalation : cho phép người dùng ứng dụng khác nhau truy cập các loại tài nguyên tương tự. Các cơ chế này chỉ hạn chế quyền truy cập vào tài nguyên đối với nhóm người dùng được phép truy cập tài nguyên. Ví dụ: một ứng dụng ngân hàng cho phép khách hàng xem hồ sơ giao dịch của họ nhưng không cho phép người dùng khác xem. Kiểm soát truy cập ngang bị hỏng cho phép kẻ tấn công truy cập tài nguyên thuộc về người dùng khác và do kiểm soát ID không đúng.
  
  - Context-Dependent Privilege Escalation : 
  
    Một số cuộc tấn công leo thang đặc quyền phụ thuộc vào ngữ cảnh bao gồm:

     + Insecure Direct Object Reference : Tham chiếu đối tượng trực tiếp không an toàn

     + Multi-step attacks Tấn công nhiều bước

     + Attacks on referer-based mechanisms :Các cuộc tấn công vào các cơ chế dựa trên giới thiệu

     + Attacks on geographical location-based mechanisms :Tấn công vào cơ chế dựa trên vị trí địa lý
  
  
  
  ### Preventing Broken Access Control Vulnerabilities
  Kiểm soát truy cập bị hỏng là một lỗ hổng được xếp hạng cao trong danh sách OWASP được đánh giá là thỉnh thoảng xảy ra, có khả năng khai thác trung bình và có tác động cực kỳ sâu sắc và có hại.
  
  Cho nên chúng ta cần phải ngăn chặn lỗ hổng này, sau đây 1 vài phương pháp để ngăn chặn các cuộc tấn công kiểm soát truy cập.
  
    + Deny by Default : Một nguyên tắc chung với kiểm soát truy cập là bắt đầu với các chức năng đặc
    quyền tối thiểu được yêu cầu. Ví dụ: theo mặc định, mọi người dùng của ứng dụng sẽ bị từ chối 
    quyền truy cập vào tài nguyên ứng dụng, chỉ người dùng hợp pháp mới có quyền xem, truy cập và sửa
    đổi chúng. Ngoài ra, các nhóm bảo mật nên xóa quyền quản trị và các đặc quyền nâng cao khác, giảm phạm vi ảnh hưởng khi kẻ tấn công có được thông tin đăng nhập của người dùng. Quản lý phiên cũng nên được thực hiện bằng cách sử dụng truy cập Just in Time để loại bỏ nhu cầu về các đặc quyền liên tục mà tin tặc có thể nhanh chóng lấy được.
  
    + Central Interface for Application-wide Access Controls : Phải có một giao diện trung tâm, được quản lý để ghi lại các sơ đồ kiểm soát truy cập được sử dụng và hỗ trợ trong việc thiết kế một khuôn khổ được sử dụng để kiểm tra sự thành công của các cơ chế kiểm soát truy cập đã thiết lập.

    + Handle Access Controls At Server-Side : Các tổ chức chỉ nên tin tưởng xác thực và ủy quyền phía máy chủ vì tổ chức áp dụng các biện pháp kiểm soát giống nhau cho tất cả các dịch vụ, người dùng và ứng dụng. Các nhóm bảo mật và nhà phát triển nên phát triển một khuôn mẫu để phân tách các nhiệm vụ.
    
    + Constant Testing and Auditing of Access Controls : Điều quan trọng là làm cho việc kiểm tra bảo mật trở thành một quy trình liên tục, nhất quán bằng cách liên tục kiểm tra và đánh giá các cơ chế kiểm soát truy cập để đảm bảo chúng hoạt động như dự định. Ngoài ra, kiểm tra hiệu quả giúp các nhóm xác định các lỗ hổng và lỗ hổng mới hơn khi chúng xuất hiện, nâng cao sự tự tin của tổ chức trong việc triển khai kiểm soát truy cập của họ.
    
    + Clean Code with Binary Access Controls : Mọi nhà phát triển phải đảm bảo rằng mã nguồn của họ bao gồm các quy tắc khai báo quyền truy cập cho mọi tài nguyên ở cấp mã và quyền truy cập phải bị từ chối theo mặc định. Điều quan trọng nữa là sử dụng các phương pháp mã hóa an toàn để tránh các lỗi lập trình phổ biến mà những kẻ tấn công nhắm mục tiêu để truy cập đặc quyền.
    
    + Enable RBAC : Kiểm soát truy cập dựa trên vai trò (RBAC) cho phép các tổ chức nhanh chóng triển khai kiểm soát truy cập bằng cách nhóm người dùng vào các vai trò và xác định các quyền liên quan đến từng vai trò. Với RBAC, các nhóm bảo mật có thể giảm bớt công việc hỗ trợ và quản trị CNTT, tối đa hóa hiệu quả hoạt động và cải thiện sự tuân thủ thông qua quản lý truy cập dữ liệu.
    
    + Enforce Record Ownership : Các tổ chức nên lập mô hình kiểm soát quyền truy cập liên kết từng bản ghi với User ID của tài khoản để thực hiện các tác vụ, thay vì cho phép người dùng truy cập, sửa đổi, cập nhật hoặc xóa bất kỳ bản ghi nào.
    
    
    
    
### PortSwigger Lab

### Lab: Unprotected admin functionality
```
This lab has an unprotected admin panel.
Solve the lab by deleting the user carlos. 
```
Mô tả đã khá là khó ràng, bài lab này admin panel không được bảo vệ điều đó tạo cơ điều kiện cho các hacker truy cập vào admin và thao túng quyền của admin. Trong bài lab này thì chúng ta xóa acc Carlos là sẽ được tính hoàn thành.

Đầu tiên truy cập vào lab ta sẽ thấy được 1 website bán hàng, tuy nhiên dựa vào lời gọi ý của đề bài là sẽ có admin panel tuy nhiên url như nào thì lại chưa biết, nên chúng ta sẽ gọi 1 siêu thần thú web đó là robots.txt để xác định xem có gì hay :laught: 
   /robots.txt


Chà chà chà có thêm url /administrator-panel ở đây nên chúng ta thử truy cập vào url này xem sao, thì thật bất ngờ vô luôn :| đơn giản là gì lab đã nói là unprotected rồi mà :vv


Sau đó chúng ta chỉ cần xóa acc là solve lab thôi :D 

### Lab: Unprotected admin functionality with unpredictable URL
```
This lab has an unprotected admin panel. It's located at an unpredictable location, but the location is disclosed somewhere in the application.
Solve the lab by accessing the admin panel, and using it to delete the user carlos. 
```
Lần này lab cũng không được bảo vệ, tuy nhiên url của bài này lại được giấu ở đâu đó trong trang web, tức là không phải siêu thần thú nữa rồi, phải dùng cách khác thôi :<< 


Truy cập tiếp vào bài lab chúng tha sẽ thấy giao diện chính của lab là 1 trang web bán hàng như sau : 



Sau đó vì gợi ý là năm trong web nên ta sẽ kiểm tra source code của web bằng view page source :3 



```
var isAdmin = false;
if (isAdmin) {
   var topLinksTag = document.getElementsByClassName("top-links")[0];
   var adminPanelTag = document.createElement('a');
   adminPanelTag.setAttribute('href', '/admin-6cr3fx');
   adminPanelTag.innerText = 'Admin panel';
   topLinksTag.append(adminPanelTag);
   var pTag = document.createElement('p');
   pTag.innerText = '|';
   topLinksTag.appendChild(pTag);
}
```
Và chúng thấy được đoạn js như sau, đoạn này đang kiểm tra xem người dùng có phải admin hay không 
và sau đó nhảy đến url /admin-6cr3fx để truy cập admin panel, tuy nhiên vì không có cơ chế bảo vệ nên
chúng ta có thể truy cập vào admin panel thủ công bằng cách gọi url.



Truy cập vào và xóa acc Carlos để hoàn thành yêu cầu của bài lab này :3 



### Lab: User role controlled by request parameter
```
This lab has an admin panel at /admin, which identifies administrators using a forgeable cookie.

Solve the lab by accessing the admin panel and using it to delete the user carlos.

You can log in to your own account using the following credentials: wiener:peter
```

Bài này cho sẵn panel tuy nhiên đã sử dụng cookie để phân biệt thằng nào là admin thằng nào là user ròi, nên không có chuyện đi đường đường chính chính vào admin panel được nữa phải chơi chiêu thôi :3

Truy cập vào lại thấy cái shop như này và login vào với acc đã được cấp từ trước : 


Nhảy vô admin panel thì nó đập cho cái dòng này vô mặt :| 
```
Admin interface only available if logged in as an administrator 
```

Theo như kinh nghiệm tích tụ lâu nay thì rõ ràng là trong request đã có cookie session để phân biêt có phải admin hay không rồi


Nên chúng ta dùng BurpSuite bắt request xem thử như nào 



Bất ngờ chưa cái admin nó rõ ràng luôn kìa admin=false là quyền hạn của weiner nên là muốn upgrade lên admin thì chỉnh lại true thôi



Như sau thì có thể thấy chúng ta đã vào đc admin panel



Xóa carlos và solveeeeeeee



### Lab: User role can be modified in user profile
```
 This lab has an admin panel at /admin. It's only accessible to logged-in users with a roleid of 2.
Solve the lab by accessing the admin panel and using it to delete the user carlos.
You can log in to your own account using the following credentials: wiener:peter 
```
 Bài này thì lại cho tiếp đường url /admin tuy nhiên lần này admin sẽ được định dạng bằng roleid = 2 . Thế chắc cú là roleid của acc count đc cấp là khác 2 rồi :|
  
  Tuy cập vào lab cùng account được cấp sau đó truy cập admin panel thì vẫn dòng chữ quen thuộc tuy nhiên mình đã liếc mắt soi mói thử xem roleid nó ở đâu trong cái request admin panel nhưng lại không có :|
   
  
  Thế là mình đoán rằng nó nằm đâu đó trong profile của account rồi tuy nhiên mình đang ở url /my-account rồi mà có thấy gì đâu ?? Nhưng lại có chức năng change email nên mình test thử và bắt request lại thì nó như này này :
  
  
  
  Có phải rõ ràng quá rồi không, theo như cú pháp trên request để change email thì chúng ta maybeeeeeee có thể change roleid nhi ?? suy nghĩ hợp lý mà :|
  
  
  Mà hợp lý thì thử thôi.
  
 Thử chơi chơi ai ngờ được thật, thật ra đây là do cơ chế update csdl của website nên việc mình update roleid là 1 trong những "tính năng" không mong muốn của dev thôi 🚑 
  
  
  Vì mình đã thay đổi roleid nên là việc truy cập admin panel để xóa là hoàn toàn được phép 🤠🤠🤠🤠 
  
  
  
  Solve đi nào
  
  
  
  
  ### Lab: URL-based access control can be circumvented
  ```
  This website has an unauthenticated admin panel at /admin, but a front-end system has been configured to block external access to that path. However, the back-end application is built on a framework that supports the X-Original-URL header.

To solve the lab, access the admin panel and delete the user carlos.
  ```
    
 Lần này lại là 1 sự thay đổi nho nhỏ đó chính là lần này lab đã sử dụng framework X-Original-URL, về cơ chế này thì đối với các phương thức POST sẽ deny những url như là /admin, /delete ,.... 
  
  
 Test thử cái web thì y như rằng, từ chối cái nhe. Tuy nhiên khi thử format của X-Original-URL thì thấy rằng chúng ta đã truy cập được vào admin panel èo èo èo
  
  
  
  Thế là chúng ta chỉ việc xóa Carlos như sau 🚑🚑🚑🚑
  
  
  Solveee
  
  
  
  ### 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
