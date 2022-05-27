# Mikro containerized
The right container for any of your php microservices.

### Build
```bash
$ docker build -t m4ffucci/mikro .
```

### Run
```bash
$ docker run -p 9501:9501 --name mikro m4ffucci/mikro
```

### Tag
```bash
$ docker image tag m4ffucci/mikro m4ffucci/mikro:vXYZ
```

### Publish
```bash
$ docker push --all-tags m4ffucci/mikro
```