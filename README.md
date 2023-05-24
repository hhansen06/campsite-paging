# campsite-paging
Legacy dockerstack for a paging system, original designed for a youth camp



#ToDo:
- fix auth for vnc & novnc in container mixer

## Info
### Save autojack settings
1. docker compose stop autojack
2. docker compose run autojack aj-snapshot /root/aj-settings.conf       
3. press y to override existing file.
4. docker compose start autojack