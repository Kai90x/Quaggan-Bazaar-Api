<?php

namespace KaiApp\Middleware;

/**
 * Slim Middleware to handle OAuth2 Authorization.
 */
class Authorization
{
    /**
     * OAuth2 Server
     *
     * @var OAuth2\Server
     */
    private $server;

    /**
     * Create a new instance of the Authroization middleware
     *
     * @param OAuth2\Server $server The configured OAuth2 server.
     */
    public function __construct(OAuth2\Server $server)
    {
        $this->server = $server;
        $clientRepository = new ClientRepository();
        $accessTokenRepository = new AccessTokenRepository();
        $scopeRepository = new ScopeRepository();
        $userRepository = new UserRepository();
        $refreshTokenRepository = new RefreshTokenRepository();
    }

    /**
     * Verify request contains valid access token.
     *
     * @param array $scopes Scopes required for authorization. $scopes can be given as an array of arrays. OR logic will
     *                      use with each grouping. Example: Given ['superUser', ['basicUser', 'aPermission']], the
     *                      request will be verified if the request token has 'superUser' scope OR 'basicUser' and
     *                      'aPermission' as its scope.
     * @return void
     */
    public function call(array $scopes = [null])
    {
        if (!$this->verify($scopes)) {
            MessageBridge::mapResponse($this->server->getResponse(), $this->app->response());
            $this->app->stop();
        } //@codeCoverageIgnore since stop() throws
        $this->app->token = $this->server->getResourceController()->getToken();
        if ($this->next !== null) {
            $this->next->call();
        }
    }

    /**
     * Helper method to verify a resource request, allowing return early on success cases
     *
     * @param array $scopes Scopes required for authorization.
     *
     * @return boolean True if the request is verified, otherwise false
     */
    private function verify(array $scopes = [null])
    {
        foreach ($scopes as $scope) {
            if (is_array($scope)) {
                $scope = implode(' ', $scope);
            }
            $oauth2Request = MessageBridge::newOauth2Request($this->app->request());
            if ($this->server->verifyResourceRequest($oauth2Request, null, $scope)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Allows this middleware to be used as a callable.
     *
     * @return void
     */
    public function __invoke()
    {
        $this->call();
    }

    /**
     * Returns a callable function to be used as a authorization middleware with a specified scope.
     *
     * @param array $scopes Scopes require for authorization.
     *
     * @return callable
     */
    public function withRequiredScope(array $scopes)
    {
        $auth = $this;
        return function () use ($auth, $scopes) {
            return $auth->call($scopes);
        };
    }
}