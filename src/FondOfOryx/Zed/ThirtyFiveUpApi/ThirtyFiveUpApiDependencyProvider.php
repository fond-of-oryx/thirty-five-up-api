<?php

namespace FondOfOryx\Zed\ThirtyFiveUpApi;

use FondOfOryx\Zed\ThirtyFiveUpApi\Dependency\Facade\ThirtyFiveUpApiToThirtyFiveUpFacadeBridge;
use FondOfOryx\Zed\ThirtyFiveUpApi\Dependency\Facade\ThirtyFiveUpApiToThirtyFiveUpFacadeInterface;
use FondOfOryx\Zed\ThirtyFiveUpApi\Dependency\QueryContainer\ThirtyFiveUpApiToApiQueryBuilderContainerBridge;
use FondOfOryx\Zed\ThirtyFiveUpApi\Dependency\QueryContainer\ThirtyFiveUpApiToApiQueryBuilderContainerInterface;
use FondOfOryx\Zed\ThirtyFiveUpApi\Dependency\QueryContainer\ThirtyFiveUpApiToApiQueryContainerBridge;
use FondOfOryx\Zed\ThirtyFiveUpApi\Dependency\QueryContainer\ThirtyFiveUpApiToApiQueryContainerInterface;
use Orm\Zed\ThirtyFiveUp\Persistence\FooThirtyFiveUpOrderQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ThirtyFiveUpApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_API = '35UP:QUERY_CONTAINER_API';

    /**
     * @var string
     */
    public const QUERY_BUILDER_CONTAINER_API = '35UP:QUERY_BUILDER_CONTAINER_API';

    /**
     * @var string
     */
    public const FACADE_THIRTY_FIVE_UP = '35UP:FACADE_THIRTY_FIVE_UP';

    /**
     * @var string
     */
    public const QUERY_THIRTY_FIVE_UP_ORDER = '35UP:QUERY_THIRTY_FIVE_UP_ORDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addApiQueryContainer($container);
        $container = $this->addThirtyFiveUpFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addApiQueryBuilderContainer($container);
        $container = $this->addApiQueryContainer($container);
        $container = $this->addThirtyFiveUpFacade($container);
        $container = $this->addThirtyFiveUpOrderQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_API] = static function (Container $container): ThirtyFiveUpApiToApiQueryContainerInterface {
            return new ThirtyFiveUpApiToApiQueryContainerBridge($container->getLocator()->api()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiQueryBuilderContainer(Container $container): Container
    {
        $container[static::QUERY_BUILDER_CONTAINER_API] = static function (Container $container): ThirtyFiveUpApiToApiQueryBuilderContainerInterface {
            return new ThirtyFiveUpApiToApiQueryBuilderContainerBridge($container->getLocator()->apiQueryBuilder()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addThirtyFiveUpFacade(Container $container): Container
    {
        $container[static::FACADE_THIRTY_FIVE_UP] = static function (Container $container): ThirtyFiveUpApiToThirtyFiveUpFacadeInterface {
            return new ThirtyFiveUpApiToThirtyFiveUpFacadeBridge($container->getLocator()->thirtyFiveUp()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addThirtyFiveUpOrderQuery(Container $container): Container
    {
        $self = $this;
        $container[static::QUERY_THIRTY_FIVE_UP_ORDER] = static function () use ($self): FooThirtyFiveUpOrderQuery {
            return $self->createThirtyFiveUpOrderQuery();
        };

        return $container;
    }

    /**
     * @return \Orm\Zed\ThirtyFiveUp\Persistence\FooThirtyFiveUpOrderQuery
     */
    protected function createThirtyFiveUpOrderQuery(): FooThirtyFiveUpOrderQuery
    {
        return FooThirtyFiveUpOrderQuery::create();
    }
}
