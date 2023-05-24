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

### allow source in database
1. docker compose exec -it database
2. mysql -u root -p
3. use durchsagen;
4. update durchsagen_quellen set erlaubt = 1;