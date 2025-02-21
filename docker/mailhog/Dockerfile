FROM --platform=linux/arm64/v8 golang:1.19-alpine as builder

RUN apk --no-cache add git
RUN go install github.com/mailhog/MailHog@latest

FROM --platform=linux/arm64/v8 alpine:latest

COPY --from=builder /go/bin/MailHog /usr/local/bin/MailHog

EXPOSE 1025 8025

ENTRYPOINT ["MailHog"]