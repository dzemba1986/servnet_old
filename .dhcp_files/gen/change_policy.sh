#!/bin/bash
user=ra-daniel
pass=$(openssl enc -base64 -d <<< TXVzdGFuZzE5ODYuCg==)
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.4.121 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.4.155 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.4.185 << DUPA
en
conf t
interface port1.0.10
egress-rate-limit 508032k
exit
interface port1.0.11
egress-rate-limit 508032k
exit
interface port1.0.12
egress-rate-limit 508032k
exit
interface port1.0.13
egress-rate-limit 508032k
exit
interface port1.0.14
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.9
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.4.21 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
interface port1.0.9
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.4.240 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.4.43 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.101 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.107 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.108 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.113 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.114 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.116 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.12 << DUPA
en
conf t
interface port1.0.10
egress-rate-limit 508032k
exit
interface port1.0.13
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.123 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.124 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.133 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.137 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.143 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.145 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.146 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.148 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.166 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.168 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.184 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.185 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.187 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.205 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.21 << DUPA
en
conf t
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.233 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.234 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.236 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.238 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.239 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.245 << DUPA
en
conf t
interface port1.0.10
egress-rate-limit 508032k
exit
interface port1.0.11
egress-rate-limit 508032k
exit
interface port1.0.12
egress-rate-limit 508032k
exit
interface port1.0.13
egress-rate-limit 508032k
exit
interface port1.0.15
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
interface port1.0.9
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.69 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.74 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.75 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.77 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.84 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.86 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.87 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.89 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.92 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.93 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.5.94 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.131 << DUPA
en
conf t
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.147 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.176 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.177 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.10
egress-rate-limit 508032k
exit
interface port1.0.12
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
interface port1.0.9
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.178 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.179 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.9
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.181 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.182 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.10
egress-rate-limit 508032k
exit
interface port1.0.11
egress-rate-limit 508032k
exit
interface port1.0.12
egress-rate-limit 508032k
exit
interface port1.0.13
egress-rate-limit 508032k
exit
interface port1.0.14
egress-rate-limit 508032k
exit
interface port1.0.16
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.9
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.184 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.185 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.186 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.190 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.194 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.197 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.198 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.199 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.201 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.202 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.204 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.205 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.206 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.207 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.208 << DUPA
en
conf t
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.209 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.211 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.212 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.213 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.214 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.216 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.217 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.219 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.220 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.221 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.222 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.223 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.224 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.226 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.14
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.227 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.228 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.229 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.23 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.230 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.231 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.233 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.234 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.235 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.236 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.237 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.238 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.239 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.240 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.241 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.242 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.243 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.244 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.245 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.246 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.247 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.248 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.249 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.250 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.251 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.252 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.253 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.254 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.255 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.55 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.60 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.82 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.9 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.95 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.6.97 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.1 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.10 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.12 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
interface port1.0.6
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.13 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.14 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.18 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.2 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.10
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.7
egress-rate-limit 508032k
exit
interface port1.0.8
egress-rate-limit 508032k
exit
interface port1.0.9
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.25 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.26 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.28 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.29 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.3 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.38 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.39 << DUPA
en
conf t
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.4 << DUPA
en
conf t
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.43 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.46 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.49 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.5 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
interface port1.0.5
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.6 << DUPA
en
conf t
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
interface port1.0.4
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.8 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
exit
wr
DUPA
sshpass -p $pass ssh -T -p22222 -o StrictHostKeyChecking=no $user@172.20.7.9 << DUPA
en
conf t
interface port1.0.1
egress-rate-limit 508032k
exit
interface port1.0.2
egress-rate-limit 508032k
exit
interface port1.0.3
egress-rate-limit 508032k
exit
exit
wr
DUPA
