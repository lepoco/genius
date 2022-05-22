// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Grpc.Net.Client;

namespace Genius.Client.Interfaces;

/// <summary>
/// Describes a universal connection with the Genius micro-services available to other gRPC services.
/// </summary>
public interface IChannel
{
    /// <summary>
    /// The address of the micro-service to which the class should connect.
    /// </summary>
    public string ChannelAddress { get; set; }

    /// <summary>
    /// Takes the globally available Genius gRPC channel.
    /// </summary>
    public GrpcChannel GetGeniusChannel();

    /// <summary>
    /// Takes the globally available OAuth gRPC channel.
    /// </summary>
    public GrpcChannel GetOAuthChannel();

    /// <summary>
    /// Takes the globally available statistics gRPC channel.
    /// </summary>
    public GrpcChannel GetStatisticsChannel();

    /// <summary>
    /// Gets or creates a Genius gRPC client instance of specified type.
    /// </summary>
    public T GetGeniusClient<T>() where T : Grpc.Core.ClientBase;
}

