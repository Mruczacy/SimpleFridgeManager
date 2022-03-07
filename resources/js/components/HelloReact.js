import React from 'react';
import ReactDOM from 'react-dom';


class HelloReact extends React.Component {
    render() {
        return (
            <div className = "container hello-react">Czytasz to o: {this.props.time}.</div>
        );
    }
}

class Welcome extends React.Component {
    render() {
        return (
            <div className = "container hello-react">Kto śmierdzi?: {this.props.data.name} Kiedy?: {this.props.data.time}.</div>
        );
    }
}

class App extends React.Component {
    render() {
        return (
            <div>
                <div>{this.props.data.time}</div>
                <HelloReact time={this.props.data.time}/>
                <Welcome data={this.props.data} />
            </div>
        );
    }
}

let data = {
    time: new Date().toLocaleTimeString(),
    name: 'Krzysztof'
}

if(document.getElementById('HelloReact')) {
    ReactDOM.render(<App data={data}/>, document.getElementById('HelloReact'));
}

setInterval(function () {
    if(document.getElementById('HelloReact')) {
        data = {
            time: new Date().toLocaleTimeString(),
            name: 'chuj'
        }
        ReactDOM.render(<App data={data}/>, document.getElementById('HelloReact'));
    }
});
