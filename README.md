# movies4u

A media Api developed with OOP php.
## Api base
```
/api/v1/
```
### music
```
GET: /api/v1/music
```
retrieves all music records(1000)

```
GET: /api/v1/music/limit/{lim}
```
retrieves music with limit

```
GET: /api/v1/music/popular/{lim}
```
retrieves popular music with limit. params: (int) lim

```
GET: /api/v1/music/url/{short_url}
```
retrieves a particular music. params: short_url




### movies
```
GET: /api/v1/videos
```
this endpoint retrieves all music records(1000)

### series
```
GET: /api/v1/series
```
this endpoint retrieves all music records(1000)
