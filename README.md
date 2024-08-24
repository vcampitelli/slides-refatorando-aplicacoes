# Refatorando Aplicações na Prática

## Slides

Acesse os slides hospedados em [viniciuscampitelli.com/slides-refatorando-aplicacoes](https://viniciuscampitelli.com/slides-refatorando-aplicacoes) ou clone este repositório e acesse o arquivo [`docs/index.html`](./docs/index.html) em seu navegador.

```shell
git clone --recursive git@github.com:vcampitelli/slides-refatorando-aplicacoes.git
```

> Note que é preciso informar o parâmetro `--recursive` para que os slides funcionem corretamente

## Mãos na massa

1. Certifique-se que você tenha o [Docker](https://docs.docker.com/get-docker) e o [Docker Compose](https://docs.docker.com/compose) instalados
2. Clone este repositório

    ```shell
    git clone git@github.com/vcampitelli/slides-refatorando-aplicacoes.git
    ```
3. Inicialize os _containers_ da aplicação

    ```shell
    cd demo/app-antiga
    docker compose up -d
    ```

4. Acesse [localhost:8080](http://localhost:8080) em seu navegador
