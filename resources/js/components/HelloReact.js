import React from 'react';
import ReactDOM from 'react-dom';

export default function HelloReact() {
    return (
        <div className = "container hello-react">HelloReact</div>
    );
}
if(document.getElementById('HelloReact')) {
    ReactDOM.render(<HelloReact />, document.getElementById('HelloReact'));
}
