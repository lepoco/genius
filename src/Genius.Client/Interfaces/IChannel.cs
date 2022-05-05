// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Grpc.Net.Client;

namespace Genius.Client.Interfaces
{
    /// <summary>
    /// Describes a universal connection with the Genius microservice available to other gRPC services.
    /// </summary>
    public interface IChannel
    {
        /// <summary>
        /// The address of the microservice to which the class should connect.
        /// </summary>
        public string ChannelAddress { get; set; }

        /// <summary>
        /// Takes the globally available gRPC channel.
        /// </summary>
        public GrpcChannel GetChannel();

        /// <summary>
        /// Gets or creates a gRPC client instance of specified type.
        /// </summary>
        public T GetClient<T>() where T : Grpc.Core.ClientBase;
    }
}
