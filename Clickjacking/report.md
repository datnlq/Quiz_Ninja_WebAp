# Clickjacking

Nhân dịp trên mạng đang nổi trội những người thân bạn bè pay acc fb ngay trong 1 đêm thì mình có cảm hứng để làm về lổ hỗng [Clickjacking](https://portswigger.net/web-security/clickjacking)

## Clickjacking là gì ?
Nói ngắn gọn dễ hiểu nhất là một hình thức tấn công đánh lừa người dùng nhấp chuột vô ý vào một đối tượng trên website(ví dụ cụ thể ở đây là fb). Khi nhấp chuột vào một đối tượng trên màn hình, người dùng nghĩ là mình đang click vào đối tượng đó nhưng thực chất họ đang bị lừa click vào một đối tượng khác > đã bị làm mờ hay ẩn đi. Kẻ tấn công có thể sử dụng kỹ thuật tấn công này cho nhiều mục đích. Đánh cắp tài khoản người dùng, lừa click vào quảng cáo để kiếm tiền, lừa like page hoặc nguy hiểm hơn là cài một webshell lên máy chủ web.
## Có thể khai thác lỗ hổng này như thế nào ??
```
  + Hacker có thể lấy cắp thông tin đăng nhập của bạn thông qua 1 cái form fake trông có vẻ giống giống trang thật.
  + Lừa người dùng mở web-cam hoặc microphone bằng cách hiển thị các yếu tố vô hình trên trang cài đặt Adobe Flash.
  + Phát tán worms trên các trang mạng xã hội.
  + Phát tán malware bằng cách chuyển hướng người dùng tới link download chương trình độc hại.
  + Quảng cáo lừa đảo.
```
## Cách ngăn chặn Clickjacking

Clickjacking tấn công bằng cách bao bọc trang web mà người dùng tin tưởng bởi <iframe> sau đó render ẩn phần tử này lên trên cùng. Để ngăn chặn lỗ hổng này các bạn có thể sử dụng các cách sau :
```
  + X-Frame-Options
  + Content Security Policy
  + Frame-Killing
```
  
  
## Clickjacking Lab
  
### 
