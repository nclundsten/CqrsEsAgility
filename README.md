**CqrsEsAgility**

Generate many common files needed by providing a simple config
- Command  (extends Prooph\Common\Messaging\Command)
- CommandHandler
- Event    (extends Prooph\EventSourcing\AggregateChanged)
- EventListener
    - can add additional commands
- Projector

*todo* 
- generate factories & interfaces where needed
- tests
- generate tests for files created


*usage*

there's a provided "hockey" sample config, which is enough to get started
> php public/index.php
 
generated files are in ./generated