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
retrieves all videos records(1000)

```
GET: /api/v1/videos/limit/{lim}
```
retrieves videos with limit

```
GET: /api/v1/videos/popular/{lim}
```
retrieves popular videos with limit. params: (int) lim

```
GET: /api/v1/videos/url/{short_url}
```
retrieves a particular videos. params: short_url

### series
```
GET: /api/v1/series
```
retrieves all series records(1000)

```
GET: /api/v1/series/limit/{lim}
```
retrieves series with limit

```
GET: /api/v1/series/popular/{lim}
```
retrieves popular series with limit. params: (int) lim

```
GET: /api/v1/series/url/{short_url}
```
retrieves a particular series. params: short_url
